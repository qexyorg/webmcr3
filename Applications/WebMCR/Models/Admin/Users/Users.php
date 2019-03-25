<?php

namespace App\WebMCR\Models\Admin\Users;

use App\WebMCR\Models\Logger;
use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use App\WebMCR\Models\User\UserHelper;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\File\File;
use Framework\Components\Filters\_Input;
use Framework\Components\Pagination\Pagination;
use Framework\Components\Path;
use Framework\Components\Permissions\Permissions;

class Users {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function getCount($params){
		$cache = Cache::getOnce([__METHOD__, $params]);

		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('users');

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $params], $result);
		}

		$array = $select->getArray();

		if(empty($array)){
			return Cache::setOnce([__METHOD__, $params], $result);
		}

		$result = intval(@$array[0][0]);

		return Cache::setOnce([__METHOD__, $params], $result);
	}

	public function getPagination($params){

		$cache = Cache::getOnce(__METHOD__);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$pagination->setCount($this->getCount($params))
			->setCurrentPage(@$params['page_id'])
			->setLimit($config['pagination']['admin']['users'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'admin/users/page-{PAGE}');

		return Cache::setOnce(__METHOD__, $pagination);
	}

	public function getList($params){

		$pagination = $this->getPagination($params);

		$result = [];

		$select = Database::select()
			->columns(['`u`.*',
				'`ug`.`title`' => '`group_title`', '`ug`.`name`' => '`group_name`', '`ug`.`text`' => '`group_text`'])
			->from(['u' => 'users'])
			->leftjoin('user_groups', 'ug', ["`ug`.`id`=`u`.`group_id`"])
			->order(['`u`.`id`' => 'DESC'])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		return $select->getAssoc();
	}

	public function getItem($id){
		$id = intval($id);

		$cache = Cache::getOnce([__METHOD__, $id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`u`.*',
				'`ug`.`title`' => '`group_title`', '`ug`.`name`' => '`group_name`', '`ug`.`text`' => '`group_text`'])
			->from(['u' => 'users'])
			->leftjoin('user_groups', 'ug', ["`ug`.`id`=`u`.`group_id`"])
			->where(["`u`.`id`='?'"], [$id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $id], null);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){ return Cache::setOnce([__METHOD__, $id], null); }

		return Cache::setOnce([__METHOD__, $id], $ar[0]);
	}

	public function existsByName($name, $id=null){

		$where = ["`name`='?'"];
		$where_values = [$name];

		if(!is_null($id)){
			$where[] = "`id`!='?'";
			$where_values[] = intval($id);
		}

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('news')
			->where($where, $where_values);

		if(!$select->execute()){
			return false;
		}

		$array = $select->getArray();

		if(empty($array) || !isset($array[0][0])){
			return false;
		}

		return (intval($array[0][0])<=0) ? false : true;
	}

	public function remove($id){
		$id = intval($id);

		$delete = Database::delete()
			->from('users')
			->where(["`id`='?'"], [$id]);

		Logger::base('[ПУ] Удаление пользователя', "Было произведено удаление пользователя #{$id}", __METHOD__, 'admin_users_remove');

		return $delete->execute();
	}

	public function removePermissionLinksByUser($user_id){
		$user_id = intval($user_id);

		$delete = Database::delete()
			->from('user_permissions')
			->where(["`user_id`='?'"], [$user_id]);

		return $delete->execute();
	}

	public function insertPermissionLinks($user_id, $permissions){

		if(empty($permissions)){
			return true;
		}

		$values = [];

		$time = time();
		$current_user_id = $this->getUser()->getID();

		foreach($permissions as $k => $v){
			$values[] = [$user_id, $k, $v, $current_user_id, $current_user_id, $time, $time];
		}

		$insert = Database::insert()
			->into('user_permissions')
			->columns(['user_id', 'permission_id', 'value', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values($values);

		return $insert->execute();
	}

	public function editSubmit($item_id, $params){
		$item_id = intval($item_id);

		$item = $this->getItem($item_id);

		if(is_null($item)){
			return [
				'type' => false,
				'title' => 'Ошибка 404',
				'text' => 'Страница не найдена'
			];
		}

		$filter = new _Input($params);

		$filter->add('group', _Input::TYPE_INTEGER, 1, 10, true, 1)
			->add('login', _Input::TYPE_STRING, 3, 32, true)
			->add('email', _Input::TYPE_EMAIL, 3, 128, true)
			->add('password', _Input::TYPE_STRING, 6, 128)
			->add('gender', _Input::TYPE_INTEGER, 1, 1, true, 0)
			->add('firstname', _Input::TYPE_STRING, 1, 32)
			->add('lastname', _Input::TYPE_STRING, 1, 32)
			->add('avatar', _Input::TYPE_STRING, 1, 255)
			->add('birthday', _Input::TYPE_STRING, 1, 10)
			->add('about', _Input::TYPE_STRING, 1, 65535)
			->add('permissions', _Input::TYPE_STRING_ARRAY, 1, 64);

		$data = $filter->filter();

		if(!$filter->isValid()){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Поля формы заполнены неверно'
			];
		}

		$groupsModel = new Groups();

		if(!$groupsModel->existsByID($data['group'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Выбранная группа недоступна'
			];
		}

		if(!preg_match("/^[a-z0-9_]+$/i", $data['login'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Неверный формат логина'
			];
		}

		if(!preg_match("/^[a-z0-9\.]+\@[a-z0-9\-\.]+$/i", $data['email'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Неверный формат E-Mail адреса'
			];
		}

		if(!preg_match("/^\w+$/iu", $data['firstname'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Неверный формат имени'
			];
		}

		if(!preg_match("/^\w+$/iu", $data['lastname'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Неверный формат фамилии'
			];
		}

		$config = RouterHelper::getAppConfig();

		if($item['avatar']!==$data['avatar']){
			//$config['meta']['site_url']
			if(!empty($data['avatar'])){
				$extensions = explode(',', Permissions::get('profile_avatar_extensions'));
				$pathinfo = pathinfo($data['avatar']);

				if(!in_array($pathinfo['extension'], $extensions)){
					return [
						'type' => false,
						'title' => 'Внимание!',
						'text' => 'Недопустимый формат аватара'
					];
				}
				$avatarpath = Path::to("/tmp/{$pathinfo['basename']}");
				if(!file_exists($avatarpath)){
					return [
						'type' => false,
						'title' => 'Внимание!',
						'text' => 'Временный файл не найден'
					];
				}

				$oldname = Path::to("/Public/WebMCR/Uploads/avatars/".basename($item['avatar']));

				if(!empty($item['avatar']) && file_exists($oldname)){
					@unlink($oldname);
				}

				@rename($avatarpath, Path::to("/Public/WebMCR/Uploads/avatars/{$data['login']}.{$pathinfo['extension']}"));

				$data['avatar'] = "{$config['meta']['site_url']}Uploads/avatars/{$data['login']}.{$pathinfo['extension']}";
			}
		}

		$data['birthday'] = strtotime(@$data['birthday']);

		$data['gender'] = (intval($data['gender'])) ? 1 : 0;

		if(!$this->removePermissionLinksByUser($item_id)){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка удаления устаревших привилегий пользователя'
			];
		}

		$data['permissions'] = $groupsModel->filterPermissionsByTypes($data['permissions']);

		if(!$this->insertPermissionLinks($item_id, $data['permissions'])){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка добавления привилегий'
			];
		}

		if(!$this->updateUser($item_id, $data['group'], $data['password'], $data['email'], $data['login'], $data['gender'], $data['firstname'], $data['lastname'], $data['avatar'], $data['birthday'], $data['about'])){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка сохранения пользователя'
			];
		}

		Logger::base('[ПУ] Изменение пользователя', "Было произведено изменение пользователя #{$item_id}", __METHOD__, 'admin_users_edit');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Пользователь успешно сохранен',
		];
	}

	public function updateUser($id, $group_id, $password, $email, $login, $gender, $firstname, $lastname, $avatar, $birthday, $about){
		$id = intval($id);

		$params = [
			'group_id' => $group_id,
			'login' => $login,
			'email' => $email,
			'gender' => $gender,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'avatar' => $avatar,
			'birthday' => $birthday,
			'about' => $about
		];

		if(!empty($password)){
			$params['password'] = $password;
		}

		return $this->getUser()->updateUser($params, $id);
	}

	public function ban($id, $value=0){
		$id = intval($id);

		$value = (intval($value)==1) ? 1 : 0;

		$config = RouterHelper::getAppConfig();

		$group_id = ($value) ? $config['changegroup']['ban'] : $config['changegroup']['back'];

		$delete = Database::update()
			->table('users')
			->set(['`group_id`' => $group_id, '`ip_update`' => UserHelper::getIP(), '`date_update`' => time()])
			->where(["`id`='?'"], [$id]);

		Logger::base('[ПУ] Блокировка пользователя', "Была произведена блокировка пользователя #{$id}", __METHOD__, 'admin_users_ban');

		return $delete->execute();
	}

	public function isBannedIP($ip){
		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('user_banip')
			->where(["`ip`='?'"], [$ip]);

		if(!$select->execute()){
			return false;
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return false;
		}

		return (intval(@$ar[0][0])>0);
	}

	public function insertBanIP($ip, $reason=''){
		$time = time();
		$user_id = $this->getUser()->getID();

		$insert = Database::insert()
			->into('user_banip')
			->columns(['ip', 'reason', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$ip, $reason, $user_id, $user_id, $time, $time]);

		return $insert->execute();
	}

	public function banip($params){

		$filter = new _Input($params);

		$filter->add('ip', _Input::TYPE_STRING, 1, 15, true)
			->add('reason', _Input::TYPE_STRING, 1, 65535);

		$data = $filter->filter();

		if(empty($data['ip'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Не заполнено поле IP адреса'
			];
		}

		if($this->isBannedIP($data['ip'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'IP адрес уже заблокирован ранее'
			];
		}

		if(!$this->insertBanIP($data['ip'], $data['reason'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Произошла ошибка добавления IP адрес в список заблокируемых'
			];
		}

		Logger::base('[ПУ] Блокировка IP', "Была произведена блокировка IP пользователя {$data['ip']}", __METHOD__, 'admin_users_banip');

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'IP адрес был успешно заблокирован'
		];
	}

	public function uploadAvatar($files){

		if(!isset($files['avatar']) || empty($files['avatar'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Изображение не выбрано'
			];
		}

		$extensions = str_replace(' ', '', Permissions::get('profile_avatar_extensions'));
		$extensions = explode(',', $extensions);

		$minSize = explode('x', Permissions::get('profile_avatar_min_size'));
		$maxSize = explode('x', Permissions::get('profile_avatar_max_size'));

		$size = @getimagesize($files['avatar']['tmp_name']);

		if(!$size){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Неверный формат файла'
			];
		}

		if($minSize[0]<$size[0] || $minSize[1]<$size[1] || $maxSize[0]>$size[0] || $maxSize[1]>$size[1]){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Недопустимое разрешение изображения'
			];
		}

		$upload = File::upload()
			->extensions($extensions)
			->maxFileSize(Permissions::get('profile_avatar_max_filesize'))
			->setUploadPath(Path::to('/tmp'))
			->maxFiles(1)
			->files($files['avatar'])
			->setRandomName(10, 16);

		if(!$upload->execute()){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Ошибка загрузки изображения'
			];
		}

		$paths = $upload->getPaths();
		$names = $upload->getNames();

		$filter = File::image()
			->source($paths[0])
			->filename(Path::to("/tmp/{$names[0]}"))
			->scale(1)
			->minWidth($minSize[0])
			->minHeight($minSize[1])
			->maxWidth($maxSize[0])
			->maxHeight($maxSize[1]);

		if(!$filter->execute()){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Ошибка фильтрации изображения'
			];
		}

		$config = RouterHelper::getAppConfig();

		$url = "{$config['meta']['site_url']}Uploads/tmp/{$names[0]}";

		return [
			'type' => true,
			'title' => 'Внимание!',
			'text' => 'Поздравляем! Аватар успешно изменен',
			'avatar' => $url,
		];
	}
}

?>