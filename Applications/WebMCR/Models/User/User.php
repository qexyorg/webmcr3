<?php

namespace App\WebMCR\Models\User;

use Framework\Alonity\DI\DI;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;

class User implements UserInterface {

	const BY_TYPE_ID = 'id';
	const BY_TYPE_EMAIL = 'email';
	const BY_TYPE_LOGIN = 'login';

	const USER_COMPLETE_BY_NAME_VALUE = 0;
	const USER_COMPLETE_BY_NAME_FULL = 1;
	const USER_COMPLETE_BY_ID_VALUE = 2;
	const USER_COMPLETE_BY_ID_FULL = 3;

	/**
	 * Возвращает логику работы с пользователем
	 *
	 * @param $classname string|null
	 *
	 * @return object
	*/
	final public function getLogic($classname=null){
		$classname = (is_null($classname)) ? UserHelper::getUserLogic() : $classname;

		if(DI::has([__METHOD__, $classname])){
			return DI::get([__METHOD__, $classname]);
		}

		if(!class_exists($classname)){
			return null;
		}

		$class = new $classname();

		DI::set([__METHOD__, $classname], $class);

		return $class;
	}

	/**
	 * Возвращает идентификатор текущего пользователя
	 *
	 * @return integer
	*/
	final public function getID(){
		return $this->getLogic()->getUserID();
	}

	/**
	 * Возращает информацию о текущем пользователе
	 *
	 * @return array|boolean
	*/
	final public function getCurrentUser(){
		return $this->getLogic()->getUser($this->getID(), self::BY_TYPE_ID);
	}

	/**
	 * Проверяет, авторизован ли пользователь
	 *
	 * @return boolean
	*/
	final public function isAuth(){
		return $this->getLogic()->isAuth();
	}

	/**
	 * Возвращает информацию о пользователе по его идентификатору
	 *
	 * @param $user_id integer
	 *
	 * @return array|boolean
	*/
	final public function getUserByID($user_id){
		return $this->getUser($user_id, self::BY_TYPE_ID);
	}

	/**
	 * Возвращает информацию о пользователе по его E-Mail адресу
	 *
	 * @param $email string
	 *
	 * @return array|boolean
	 */
	final public function getUserByEmail($email){
		return $this->getUser($email, self::BY_TYPE_EMAIL);
	}

	/**
	 * Возвращает информацию о пользователе по его логину
	 *
	 * @param $login string
	 *
	 * @return array|boolean
	 */
	final public function getUserByLogin($login){
		return $this->getUser($login, self::BY_TYPE_LOGIN);
	}

	/**
	 * Проверяет наличие пользователя
	 *
	 * @param $value string
	 * @param $type string
	 *
	 * @return boolean
	 */
	final public function userExists($value, $type='id'){
		return $this->getLogic()->userExists($value, $type);
	}

	/**
	 * Возвращает неотфильтрованную информацию о пользователе
	 *
	 * @param $value mixed
	 * @param $type string
	 *
	 * @return array
	*/
	final public function getUser($value, $type='id'){
		return $this->getLogic()->getUser($value, $type);
	}

	/**
	 * Создает пользователя
	 *
	 * @param $params array
	 *
	 * @return integer|boolean
	 */
	final public function createUser($params){
		return $this->getLogic()->createUser($params);
	}

	/**
	 * Обновляет пользователя
	 *
	 * @param $params array
	 * @param $value string|integer
	 * @param $type string
	 * @param $unhashed boolean
	 *
	 * @return integer|boolean
	 */
	final public function updateUser($params, $value=null, $type='id', $unhashed=false){
		if(is_null($value)){
			$value = $this->getID();
		}

		return $this->getLogic()->updateUser($params, $value, $type, $unhashed);
	}

	/**
	 * Создает уникальный токен пользователя
	 *
	 * @param $user_id integer|null
	 * @param $type string
	 * @param $data string
	 *
	 * @return string|boolean
	 */
	final public function createUserToken($user_id=null, $type='restore', $data=''){
		return $this->getLogic()->createUserToken($user_id, $type, $data);
	}

	/**
	 * Удаляет уникальный токен пользователя
	 *
	 * @param $user_id integer|null
	 * @param $type string
	 *
	 * @return boolean
	 */
	final public function deleteUserToken($user_id=null, $type='restore'){
		return $this->getLogic()->deleteUserToken($user_id, $type);
	}

	/**
	 * Возвращает информацию по уникальному токену
	 *
	 * @param $token string
	 *
	 * @return array|boolean
	 */
	final public function getUserToken($token){
		return $this->getLogic()->getUserToken($token);
	}

	/**
	 * Удаляет пользователя
	 *
	 * @param $value string|integer
	 * @param $type
	 *
	 * @return boolean
	 */
	final public function deleteUser($value, $type='id'){
		if(is_null($value)){
			$value = $this->getID();
		}

		return $this->getLogic()->deleteUser($value, $type);
	}

	/**
	 * Возвращает UUID пользователя
	 *
	 * @param $string string
	 * @param $offline boolean
	 *
	 * @return string
	*/
	public function uuid($string, $offline=true){

		$cache = Cache::getOnce([__METHOD__, $string, $offline]);

		if(!is_null($cache)){ return $cache; }

		$string = ($offline) ? "OfflinePlayer:".$string : mb_strtolower($string, "UTF-8");
		$val = md5($string, true);
		$byte = array_values(unpack('C16', $val));

		$tLo = ($byte[0] << 24) | ($byte[1] << 16) | ($byte[2] << 8) | $byte[3];
		$tMi = ($byte[4] << 8) | $byte[5];
		$tHi = ($byte[6] << 8) | $byte[7];
		$csLo = $byte[9];
		$csHi = $byte[8] & 0x3f | (1 << 7);

		if (pack('L', 0x6162797A) == pack('N', 0x6162797A)) {
			$tLo = (($tLo & 0x000000ff) << 24) | (($tLo & 0x0000ff00) << 8) | (($tLo & 0x00ff0000) >> 8) | (($tLo & 0xff000000) >> 24);
			$tMi = (($tMi & 0x00ff) << 8) | (($tMi & 0xff00) >> 8);
			$tHi = (($tHi & 0x00ff) << 8) | (($tHi & 0xff00) >> 8);
		}

		$tHi &= 0x0fff;
		$tHi |= (3 << 12);

		$result = sprintf(
			'%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
			$tLo, $tMi, $tHi, $csHi, $csLo,
			$byte[10], $byte[11], $byte[12], $byte[13], $byte[14], $byte[15]
		);

		return Cache::setOnce([__METHOD__, $string, $offline], $result);
	}

	/**
	 * Сравнивает пароль с его хэш-суммой
	 *
	 * @param $password string
	 * @param $salt string
	 * @param $hash string
	 *
	 * @return boolean
	 */
	final public function checkPassword($password, $salt='', $hash){
		return $this->getLogic()->checkPassword($password, $salt='', $hash);
	}

	/**
	 * Генерирует хэш-сумму пароля
	 *
	 * @param $password string
	 * @param $salt string
	 *
	 * @return string
	 */
	final public function createPassword($password, $salt=''){
		return $this->getLogic()->createPassword($password, $salt);
	}

	/**
	 * Создает авторизацию пользователя
	 *
	 * @param $user_id integer|null
	 * @param $remember boolean
	 *
	 * @return boolean
	*/
	final public function setAuth($user_id, $remember=true){
		return $this->getLogic()->setAuth($user_id, $remember);
	}

	/**
	 * Сбрасывает авторизацию пользователя
	 *
	 * @param $user_id integer|null
	 *
	 * @return boolean
	*/
	final public function setUnauth($user_id=null){
		$user_id = (is_null($user_id)) ? $this->getID() : intval($user_id);

		return $this->getLogic()->setUnauth($user_id);
	}

	/**
	 * Возвращает массив балансов пользователя
	 *
	 * @param $user_id integer|null
	 * @param $isCached boolean
	 *
	 * @return array
	*/
	final public function getBalance($user_id=null, $isCached=true){
		$user_id = (is_null($user_id)) ? $this->getID() : intval($user_id);

		if($isCached){
			$cache = Cache::getOnce([__METHOD__, $user_id]);
			if(!is_null($cache)){ return $cache; }
		}

		$balance = $this->getLogic()->getBalance($user_id);

		return ($isCached) ? Cache::setOnce([__METHOD__, $user_id], $balance) : $balance;
	}

	final public function getUserPermissionLinks($user_id=null, $isCached=true, $complete=0){
		if(is_null($user_id)){
			$user_id = $this->getID();
		}

		$user_id = intval($user_id);

		$complete = intval($complete);

		$result = [[],[],[],[]];

		if(!isset($result[$complete])){ return []; }

		if($isCached){
			$cache = Cache::get(['User->getUserPermissionLinks', $user_id, $complete]);
			if(!is_null($cache)){ return $cache; }
		}

		$select = Database::select()
			->columns(['`p`.`id`', '`p`.`title`', '`p`.`name`', '`p`.`default`', '`p`.`type`', '`p`.`system`', '`up`.`value`'])
			->from(['up' => 'user_permissions'])
			->leftjoin('permissions', 'p', ["`p`.`id`=`up`.`permission_id`"])
			->where(["`up`.`user_id`='?'"], [$user_id]);

		if(!$select->execute() || $select->getNum()<=0){
			return $result[$complete];
		}

		foreach($select->getAssoc() as $ar){
			$value = $ar['value'];

			$permission_id = intval($ar['id']);

			if($ar['type']=='boolean'){
				$filterval = ($value=='true');
			}elseif($ar['type']=='integer'){
				$filterval = intval($value);
			}elseif($ar['type']=='float'){
				$filterval = floatval($value);
			}else{
				$filterval = $value;
			}

			$result[0][$ar['name']] = $filterval;

			$result[1][$ar['name']] = $ar;

			$result[2][$permission_id] = $filterval;

			$result[3][$permission_id] = $ar;
		}

		if($isCached){
			foreach($result as $k => $v){
				Cache::set(['User->getUserPermissionLinks', $user_id, $k], $v);
			}
		}

		return $result[$complete];
	}

	final public function getUserPermissions($user_id=null, $isCached=true, $complete=0){
		if(is_null($user_id)){
			$user = $this->getCurrentUser();
		}else{
			$user = $this->getUser($user_id);
		}

		$user_id = intval(@$user['id']);

		$group_id = intval(@$user['group_id']);

		$complete = intval($complete);

		$result = [[],[],[],[]];

		if(!isset($result[$complete])){ return []; }

		$userLinks = $this->getUserPermissionLinks($user_id, $isCached, 3);

		if(empty($userLinks)){
			return $this->getGroupPermissions($group_id, $isCached, $complete);
		}

		if($isCached){
			$cache = Cache::get(['User->getUserPermissions', $user_id, $complete]);
			if(!is_null($cache)){ return $cache; }
		}

		$groupPermissions = $this->getGroupPermissions($group_id, $isCached, $complete);

		foreach($groupPermissions as $ar){
			$permission_id = intval($ar['id']);

			$value = (isset($userLinks[$permission_id])) ? $userLinks[$permission_id]['value'] : $ar['default'];

			if($ar['type']=='boolean'){
				$filterval = ($value=='true');
			}elseif($ar['type']=='integer'){
				$filterval = intval($value);
			}elseif($ar['type']=='float'){
				$filterval = floatval($value);
			}else{
				$filterval = $value;
			}

			$result[0][$ar['name']] = $filterval;

			$result[1][$ar['name']] = $ar;

			$result[2][$permission_id] = $filterval;

			$result[3][$permission_id] = $ar;
		}

		if($isCached){
			foreach($result as $k => $v){
				Cache::set(['User->getUserPermissions', $user_id, $k], $v);
			}
		}

		return $result[$complete];
	}

	final public function getGroupPermissions($group_id=null, $isCached=true, $complete=0){
		if(is_null($group_id)){
			$user = $this->getCurrentUser();

			$group_id = ($user===false) ? 0 : $user['group_id'];
		}

		$group_id = intval($group_id);

		$complete = intval($complete);

		$result = [[],[],[],[]];

		if(!isset($result[$complete])){ return []; }

		if($isCached){
			$cache = Cache::get(['User->getGroupPermissions', $group_id, $complete]);
			if(!is_null($cache)){ return $cache; }
		}

		$groupLinks = $this->getGroupPermissionLinks($group_id, $isCached, 3);

		$select = Database::select()
			->columns(['`p`.`id`', '`p`.`title`', '`p`.`name`', '`p`.`default`', '`p`.`type`', '`p`.`system`'])
			->from(['p' => 'permissions']);

		if(!$select->execute() || $select->getNum()<=0){
			if($isCached){
				foreach($result as $k => $v){
					Cache::set(['User->getGroupPermissions', $group_id, $k], $v);
				}
			}

			return $result[$complete];
		}

		foreach($select->getAssoc() as $ar){
			$permission_id = intval($ar['id']);

			$value = (isset($groupLinks[$permission_id])) ? $groupLinks[$permission_id]['value'] : $ar['default'];

			if($ar['type']=='boolean'){
				$filterval = ($value=='true');
			}elseif($ar['type']=='integer'){
				$filterval = intval($value);
			}elseif($ar['type']=='float'){
				$filterval = floatval($value);
			}else{
				$filterval = $value;
			}

			$ar['value'] = $filterval;

			$result[0][$ar['name']] = $filterval;

			$result[1][$ar['name']] = $ar;

			$result[2][$permission_id] = $filterval;

			$result[3][$permission_id] = $ar;
		}

		if($isCached){
			foreach($result as $k => $v){
				Cache::set(['User->getGroupPermissions', $group_id, $k], $v);
			}
		}

		return $result[$complete];
	}

	final public function getGroupPermissionLinks($group_id=null, $isCached=true, $complete=0){
		if(is_null($group_id)){
			$user = $this->getCurrentUser();

			$group_id = ($user===false) ? 0 : $user['group_id'];
		}

		$group_id = intval($group_id);

		$complete = intval($complete);

		$result = [[],[],[],[]];

		if(!isset($result[$complete])){ return []; }

		if($isCached){
			$cache = Cache::get(['User->getGroupPermissionLinks', $group_id, $complete]);
			if(!is_null($cache)){ return $cache; }
		}

		$select = Database::select()
			->columns(['`p`.`id`', '`p`.`title`', '`p`.`name`', '`p`.`default`', '`p`.`type`', '`p`.`system`', '`gp`.`value`'])
			->from(['gp' => 'group_permissions'])
			->innerjoin('permissions', 'p', ["`p`.`id`=`gp`.`permission_id`"])
			->where(["`gp`.`group_id`='?'"], [$group_id]);

		if(!$select->execute() || $select->getNum()<=0){
			if($isCached){
				foreach($result as $k => $v){
					Cache::set(['User->getGroupPermissionLinks', $group_id, $k], $v);
				}
			}

			return $result[$complete];
		}

		foreach($select->getAssoc() as $ar){
			$value = $ar['value'];

			$permission_id = intval($ar['id']);

			if($ar['type']=='boolean'){
				$filterval = ($value=='true');
			}elseif($ar['type']=='integer'){
				$filterval = intval($value);
			}elseif($ar['type']=='float'){
				$filterval = floatval($value);
			}else{
				$filterval = $value;
			}

			$result[0][$ar['name']] = $filterval;

			$result[1][$ar['name']] = $ar;

			$result[2][$permission_id] = $filterval;

			$result[3][$permission_id] = $ar;
		}

		if($isCached){
			foreach($result as $k => $v){
				Cache::set(['User->getGroupPermissionLinks', $group_id, $k], $v);
			}
		}

		return $result[$complete];
	}

	/**
	 * Поиск по пользователям
	 *
	 * @param $string string
	 * @param $limit integer
	 *
	 * @return array
	 */
	final public function searchUsers($string, $limit=0){
		return $this->getLogic()->searchUsers($string, $limit);
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
		return $this->getLogic()->getUsersCount($where, $values);
	}

	private function isStatsExists($user_id=null){

		if(is_null($user_id)){
			$user_id = $this->getID();
		}

		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('user_stats')
			->where(["`user_id`='?'"], [$user_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $user_id], false);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $user_id], false);
		}

		return Cache::setOnce([__METHOD__, $user_id], (intval(@$ar[0][0])>0));
	}

	/**
	 * @param $params array
	 * @param $user_id integer|null
	 *
	 * @return boolean
	*/
	public function updateStats($params, $user_id=null){

		if(is_null($user_id)){
			$user_id = $this->getID();
		}

		$user_id = intval($user_id);

		if(empty($params)){ return false; }

		if(!$this->isStatsExists($user_id)){
			if(!Database::insert()->into('user_stats')->columns(['user_id'])->values([$user_id])->execute()){
				return false;
			}
		}

		$update = Database::update()
			->table('user_stats')
			->set($params)
			->where(["`user_id`='?'"], [$user_id]);

		return $update->execute();
	}
}

?>