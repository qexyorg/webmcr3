<?php
/**
 * Default user logic component of WebMCR 3
 *
 * @author Qexy <admin@qexy.org>
 * @copyright Copyright (c) 2018, Qexy
 * @link http://qexy.org
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version 1.0.0
 */

namespace App\WebMCR\Models\User\Logic;


use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\LogicInterface;
use App\WebMCR\Models\User\UserException;
use App\WebMCR\Models\User\UserHelper;
use Framework\Components\Cache\Cache;
use Framework\Components\Crypt\Crypt;
use Framework\Components\Database\Database;

class DefaultLogic implements LogicInterface {

	private $current = null;

	private $maxGenerateLogin = 5;

	/**
	 * @param $value mixed
	 * @param $type string
	 *
	 * @return boolean
	*/
	public function userExists($value, $type='id'){
		$cache = Cache::getOnce([__METHOD__, $value, $type]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('users')
			->where(["`{$type}`='?'"], [$value]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $value, $type], false);
		}

		$ar = $select->getArray();

		if(empty($ar) || intval($ar[0][0])<=0){ return Cache::setOnce([__METHOD__, $value, $type], false); }

		return Cache::setOnce([__METHOD__, $value, $type], true);
	}

	/**
	 * Создает хэш-сумму пароля
	 *
	 * @param $password string
	 * @param $salt string
	 *
	 * @return string
	*/
	public function createPassword($password, $salt=''){
		$config = RouterHelper::getAppConfig();

		$crypt = mb_strtolower($config['password']['algo'], 'UTF-8');

		$algos = hash_algos();

		if(in_array($crypt, $algos)){
			return hash($crypt, $password.$salt);
		}

		switch($crypt){
			case 'salted_md5': $result = Crypt::MD5($password.$salt); break;
			case 'double_md5': $result = Crypt::MD5(Crypt::MD5($password)); break;
			case 'salted_sha1': $result = Crypt::SHA1($password.$salt); break;
			case 'salted_sha256': $result = Crypt::SHA256($password.$salt); break;
			case 'salted_sha512': $result = Crypt::SHA512($password.$salt); break;
			case 'salted_crc32': $result = Crypt::CRC32($password.$salt); break;
			case 'blowfish': $result = Crypt::createPassword($password); break;

			default: $result = $password; break;
		}

		return $result;
	}

	/**
	 * Сравнивает хэш-сумму пароля со входящим паролем
	 *
	 * @param $password string
	 * @param $salt string
	 * @param $hash string
	 *
	 * @return boolean
	*/
	public function checkPassword($password, $salt='', $hash=''){
		$config = RouterHelper::getAppConfig();

		if(mb_strtolower($config['password']['algo'], 'UTF-8')=='blowfish'){
			return Crypt::checkPassword($password, $hash);
		}

		return ($this->createPassword($password, $salt)===$hash);
	}

	/**
	 * Авторизует текущего пользователя
	 *
	 * @param $user_id integer
	 * @param $remember boolean
	 *
	 * @return boolean
	*/
	public function setAuth($user_id, $remember=true){

		$time = time();

		$expire = ($remember) ? $time+31536000 : $time+UserHelper::getSessionExpire();

		$token = Crypt::MD5(Crypt::random(10, 16));

		$insert = Database::insert()
			->into('user_auth')
			->columns(['user_id', 'ip', 'token', 'date_create', 'date_expire'])
			->values([$user_id, UserHelper::getIP(), $token, $time, $expire]);

		if(!$insert->execute()){
			return false;
		}

		$_SESSION[UserHelper::getSessionName()] = $token;

		return setcookie(UserHelper::getSessionName(), $token, $expire, '/', '', UserHelper::isHTTPS(), true);
	}

	/**
	 * Производит процесс сброса авторизации пользователя
	 *
	 * @param $user_id integer|null
	 *
	 * @return boolean
	*/
	public function setUnauth($user_id=null){
		$user_id = (is_null($user_id)) ? $this->getUserID() : intval($user_id);

		$name = UserHelper::getSessionName();

		$session = '';

		if(isset($_SESSION[$name]) && $this->getUserID()==$user_id){
			$session = $_SESSION[$name];
			unset($_SESSION[$name]);
		}

		if(isset($_COOKIE[$name]) && $this->getUserID()==$user_id){
			$session = $_COOKIE[$name];
			setcookie(UserHelper::getSessionName(), '', time()-60, '/', '', UserHelper::isHTTPS(), true);
		}

		if($user_id<=0){
			return true;
		}

		$delete = Database::delete()
			->from('user_auth')
			->where(["`token`='?'", "`ip`='?'"], [$session, UserHelper::getIP()]);

		if(!$delete->execute()){
			return false;
		}

		return true;
	}

	/**
	 * Возвращает текущий ID пользователя или 0 в случае, если он не авторизован
	 *
	 * @return integer
	*/
	public function getUserID(){
		if(!is_null($this->current)){
			return $this->current['user_id'];
		}

		if(!$this->isAuth()){
			return 0;
		}

		return $this->current['user_id'];
	}

	/**
	 * Проверяет, авторизован ли пользователь или нет
	 * Устанавливает свойство $this->current['user_id']
	 *
	 * @return boolean
	*/
	public function isAuth(){

		$name = UserHelper::getSessionName();

		if(!isset($_SESSION[$name]) &&
			!isset($_COOKIE[$name])){
			return false;
		}

		$cache = Cache::getOnce(__METHOD__);
		if(!is_null($cache)){ return $cache; }

		$session = (isset($_SESSION[$name])) ? $_SESSION[$name] : $_COOKIE[$name];

		$select = Database::select()
			->columns(['`user_id`'])
			->from('user_auth')
			->where(["`token`='?'", "`date_expire`>'?'", "`ip`='?'"], [$session, time(), UserHelper::getIP()]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce(__METHOD__, false);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){ return Cache::setOnce(__METHOD__, false); }

		$this->current['user_id'] = intval($ar[0]['user_id']);

		return Cache::setOnce(__METHOD__, true);
	}

	public function getUser($value, $type='id'){
		$cache = Cache::getOnce([__METHOD__, $value, $type]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['*'])
			->from('users')
			->where(["`{$type}`='?'"], [$value]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $value, $type], false);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){ return Cache::setOnce([__METHOD__, $value, $type], false); }

		return Cache::setOnce([__METHOD__, $value, $type], $ar[0]);
	}

	/**
	 * Создает пользователя
	 *
	 * @param $params array
	 *
	 * @return array|boolean
	 */
	public function createUser($params){

		$group_id = (isset($params['group_id'])) ? $params['group_id'] : 1;

		if(!isset($params['email'])){
			return false;
		}

		$email = $params['email'];
		$expl = explode('@', $email);
		$login = preg_replace('/[^\w]+/i', '_', $expl[0]);

		$success = false;

		for($i=0; $i<$this->maxGenerateLogin; $i++){
			$try = ($i==0) ? $login : $login.Crypt::randomInt(1, 99999);

			if(!$this->userExists($try, 'login')){
				$login = $try;
				$success = true;
				break;
			}
		}

		if(!$success){
			return false;
		}

		$password = (isset($params['password'])) ? $params['password'] : Crypt::random(12, 16);

		$time = time();

		$ip = UserHelper::getIP();

		$password_hash = $this->createPassword($password);
		$gender = (isset($params['gender'])) ? intval($params['gender']) : 0;
		$firstname = (isset($params['firstname'])) ? $params['firstname'] : '';
		$lastname = (isset($params['lastname'])) ? $params['lastname'] : '';
		$avatar = (isset($params['avatar'])) ? $params['avatar'] : '';
		$birthday = (isset($params['birthday'])) ? $params['birthday'] : 0;
		$date_create = (isset($params['date_create'])) ? $params['date_create'] : $time;
		$date_update = (isset($params['date_update'])) ? $params['date_update'] : $time;

		$insert = Database::insert()
			->into('users')
			->columns(['group_id', 'email', 'password', 'login', 'gender',
				'firstname', 'lastname', 'avatar', 'birthday',
				'date_create', 'date_update', 'ip_create', 'ip_update'])
			->values([$group_id, $email, $password_hash, $login, $gender,
				$firstname, $lastname, $avatar, $birthday,
				$date_create, $date_update, $ip, $ip]);

		if(!$insert->execute()){ return false; }

		return [
			'user_id' => $insert->getLastID(),
			'password' => $password,
			'login' => $login,
			'email' => $email,
			'group_id' => $group_id
		];
	}

	/**
	 * Обновляет пользователя
	 *
	 * @param $params array
	 * @param $value string|integer
	 * @param $type string
	 * @param $unhashed boolean
	 *
	 * @throws UserException
	 *
	 * @return integer|boolean
	 */
	public function updateUser($params, $value=null, $type='id', $unhashed=false){

		if(is_null($value)){
			throw new UserException('[!WARN] value is not set');
		}

		$set = [];

		if(isset($params['group_id'])){
			$set["`group_id`"] = $params['group_id'];
		}

		if(isset($params['login'])){
			$set["`login`"] = $params['login'];
		}

		if(isset($params['email'])){
			$set["`email`"] = $params['email'];
		}

		if(isset($params['password'])){
			$set["`password`"] = ($unhashed) ? $params['password'] : $this->createPassword($params['password']);
		}

		if(isset($params['gender'])){
			$set["`gender`"] = (intval($params['gender'])==1) ? 1 : 0;
		}

		if(isset($params['firstname'])){
			$set["`firstname`"] = $params['firstname'];
		}

		if(isset($params['lastname'])){
			$set["`lastname`"] = $params['lastname'];
		}

		if(isset($params['about'])){
			$set["`about`"] = $params['about'];
		}

		if(isset($params['avatar'])){
			$set["`avatar`"] = $params['avatar'];
		}

		if(isset($params['birthday'])){
			$set["`birthday`"] = $params['birthday'];
		}

		$set["`date_update`"] = time();

		$set["`ip_update`"] = UserHelper::getIP();

		$update = Database::update()
			->table('users')
			->set($set)
			->where(["`{$type}`='?'"], [$value]);

		if(!$update->execute()){ return false; }

		return true;
	}

	/**
	 * Удаляет пользователя
	 *
	 * @param $value string|integer
	 * @param $type
	 *
	 * @return boolean
	 */
	public function deleteUser($value, $type='id'){

		$delete = Database::delete()
			->from('users')
			->where(["`{$type}`='?'"], [$value]);

		return ($delete->execute());
	}

	/**
	 * Возвращает массив балансов пользователя
	 *
	 * @param $user_login string|null
	 *
	 * @return array
	 */
	public function getBalance($user_login=null){

		$result = $columns = [];

		if(is_null($user_login)){
			$user = $this->getUser($user_login, 'login');

			if($user===false){
				return $result;
			}

			$user_login = $user['login'];
		}

		$config = RouterHelper::getAppConfig();

		if(!$config['database']['economy']['enable']){
			return $result;
		}

		foreach($config['money'] as $val){
			if(!isset($val['column'])){ continue; }

			$result[$val['column']] = 0;

			$columns[] = "`{$val['column']}`";
		}

		$select = Database::select()
			->columns($columns)
			->from($config['database']['economy']['table'])
			->where(["`{$config['database']['economy']['login_column']}`='?'"], [$user_login])
			->limit(1);

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return $result;
		}

		foreach($config['money'] as $val){
			if(!isset($val['column']) || !isset($ar[0][$val['column']])){ continue; }

			$result[$val['column']] = floatval($ar[0][$val['column']]);
		}

		return $result;
	}

	/**
	 * @param $user_id integer
	 * @param $type string
	 * @param $data string
	 *
	 * @return boolean|string
	*/
	public function createUserToken($user_id, $type='restore', $data=''){

		$token = Crypt::MD5(Crypt::random(10, 20));

		$delete = Database::delete()
			->from('user_tokens')
			->where(["`user_id`='?'", "`type`='?'"], [$user_id, $type]);

		if(!$delete->execute()){
			return false;
		}

		$insert = Database::insert()
			->into('user_tokens')
			->columns(['user_id', 'token', 'ip', 'type', 'data', 'date_create'])
			->values([$user_id, $token, UserHelper::getIP(), $type, $data, time()]);

		if(!$insert->execute()){
			return false;
		}

		return $token;
	}

	/**
	 * Удаляет уникальный токен пользователя
	 *
	 * @param $user_id integer|null
	 * @param $type string
	 *
	 * @return boolean
	 */
	public function deleteUserToken($user_id, $type='restore'){
		return Database::delete()
			->from('user_tokens')
			->where(["`user_id`='?'", "`type`='?'"], [$user_id, $type])
			->execute();
	}

	/**
	 * Возвращает информацию по уникальному токену
	 *
	 * @param $token string
	 *
	 * @return array|boolean
	 */
	public function getUserToken($token){
		$select = Database::select()
			->columns(['*'])
			->from('user_tokens')
			->where(["`token`='?'"], [$token]);

		if(!$select->execute() || $select->getNum()<=0){
			return false;
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return false;
		}

		return $ar[0];
	}

	/**
	 * Возвращает кол-во пользователей
	 *
	 * @param $where array
	 * @param $values array
	 *
	 * @return integer
	 */
	public function getUsersCount($where=[], $values=[]){
		$cache = Cache::getOnce([__METHOD__, $where, $values]);
		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('users')
			->where($where, $values);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $where, $values], $result);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $where, $values], $result);
		}

		$result = intval(@$ar[0][0]);

		return Cache::setOnce([__METHOD__, $where, $values], $result);
	}

	/**
	 * Поиск по пользователям
	 *
	 * @param $string string
	 * @param $limit integer
	 *
	 * @return array
	 */
	public function searchUsers($string, $limit){
		$limit = intval($limit);

		$select = Database::select()
			->columns(['*'])
			->from('users')
			->where(["`login` LIKE '%?%'"], [$string]);

		if($limit){ $select->limit($limit); }

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		return $select->getAssoc();
	}
}