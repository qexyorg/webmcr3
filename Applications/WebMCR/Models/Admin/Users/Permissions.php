<?php

namespace App\WebMCR\Models\Admin\Users;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Filters\_Input;
use Framework\Components\Filters\_String;
use Framework\Components\Pagination\Pagination;

class Permissions {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function getTypes(){
		return [
			'boolean' => 'Булевое значение',
			'integer' => 'Целое число',
			'float' => 'Число с плавающей запятой',
			'string' => 'Строка'
		];
	}

	public function typeExists($name){
		$types = $this->getTypes();

		return isset($types[$name]);
	}

	public function getCount($params){
		$cache = Cache::getOnce([__METHOD__, $params]);

		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('permissions');

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
			->setLimit($config['pagination']['admin']['permissions'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'admin/permissions/page-{PAGE}');

		return Cache::setOnce(__METHOD__, $pagination);
	}

	public function getList($params){

		$pagination = $this->getPagination($params);

		$result = [];

		$select = Database::select()
			->columns(['`p`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['p' => 'permissions'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`p`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`p`.`user_id_update`"])
			->order(['`p`.`id`' => 'DESC'])
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
			->columns(['`p`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['p' => 'permissions'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`p`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`p`.`user_id_update`"])
			->where(["`p`.`id`='?'"], [$id]);

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
			->from('permissions')
			->where($where, $where_values);

		if(!$select->execute()){
			return false;
		}

		$array = $select->getArray();

		if(empty($array) || !isset($array[0][0])){
			return false;
		}

		return (intval($array[0][0])>0);
	}

	public function remove($id){
		$id = intval($id);

		$permission = $this->getItem($id);

		if(is_null($permission) || intval($permission['system'])){
			return false;
		}

		$delete = Database::delete()
			->from('group_permissions')
			->where(["`permission_id`='?'"], [$id]);

		if(!$delete->execute()){
			return false;
		}

		$delete = Database::delete()
			->from('permissions')
			->where(["`id`='?'"], [$id]);

		Logger::base('[ПУ] Удаление привилегии', "Было произведено удаление привилегии #{$id}", __METHOD__, 'admin_permissions_remove');

		return $delete->execute();
	}

	public function addSubmit($params){

		$filter = new _Input($params);

		$filter->add('title', _Input::TYPE_STRING, 1, 32, true)
			->add('name', _Input::TYPE_STRING, 1, 32)
			->add('type', _Input::TYPE_STRING, 1, 32, true, 'boolean')
			->add('default', _Input::TYPE_STRING, 1, 65535);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия привилегии'
			];
		}

		if(empty($data['name'])){
			$data['name'] = _String::toLatin($data['title']);
		}

		if($this->existsByName($data['name'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Уникальное имя уже используется'
			];
		}

		if(!$this->typeExists($data['type'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Выбранный тип значения не существует'
			];
		}

		$id = $this->insertPermission($data['title'], $data['name'], $data['type'], $data['default']);

		if(!$id){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		Logger::base('[ПУ] Добавление привилегии', "Было произведено добавление привилегии #{$id}", __METHOD__, 'admin_permissions_add');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Привилегия успешно добавлена',
			'id' => $id
		];
	}

	public function insertPermission($title, $name, $type='boolean', $default='false'){

		$user_id = $this->getUser()->getID();
		$time = time();

		$insert = Database::insert()
			->into('permissions')
			->columns(['name', 'title', 'type', 'default', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$name, $title, $type, $default, $user_id, $user_id, $time, $time]);

		if(!$insert->execute()){
			return false;
		}

		return $insert->getLastID();
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

		$filter->add('title', _Input::TYPE_STRING, 1, 32, true)
			->add('name', _Input::TYPE_STRING, 1, 32)
			->add('type', _Input::TYPE_STRING, 1, 32, true, 'boolean')
			->add('default', _Input::TYPE_STRING, 1, 65535);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия привилегии'
			];
		}

		if(empty($data['name'])){
			$data['name'] = _String::toLatin($data['title']);
		}

		if(!$this->typeExists($data['type'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Выбранный тип значения не существует'
			];
		}

		if($item['name'] != $data['name']){
			if(intval($item['system'])){
				return ['type' => false, 'title' => 'Ошибка!',
					'text' => 'Редактирование уникального имени в системной привилегии недопустимо'
				];
			}

			if(empty($data['name'])){
				$data['name'] = _String::toLatin($data['title']);
			}

			if($this->existsByName($data['name'])){
				return ['type' => false, 'title' => 'Внимание!',
					'text' => 'Уникальное имя уже используется'
				];
			}
		}

		if(!$this->updatePermission($item_id, $data['title'], $data['name'], $data['type'], $data['default'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		Logger::base('[ПУ] Изменение привилегии', "Было произведено изменение привилегии #{$item_id}", __METHOD__, 'admin_permissions_edit');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Привилегия успешно сохранена',
		];
	}

	public function updatePermission($id, $title, $name, $type='boolean', $default='false'){
		$id = intval($id);

		$user_id = $this->getUser()->getID();
		$time = time();

		$update = Database::update()
			->table('permissions')
			->set(['`title`' => $title, '`name`' => $name, '`type`' => $type, '`default`' => $default,
					'`user_id_update`' => $user_id, '`date_update`' => $time])
			->where(["`id`='?'"], [$id]);

		return $update->execute();
	}

	public function getAll(){
		$select = Database::select()
			->columns(['*'])
			->from('permissions')
			->order(['title' => 'ASC']);

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		return $select->getAssoc();
	}

	public function filterPermissions($permissions){
		if(empty($permissions)){
			return [];
		}

		$permissions = explode(',', $permissions);

		$permissions = array_map('intval', $permissions);

		$permissions = array_unique($permissions);

		$permissions = implode(',', $permissions);

		$select = Database::select()
			->columns(['`id`'])
			->from('permissions')
			->where(["`id` IN (?)"], [$permissions]);

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		$permissions = [];

		foreach($select->getAssoc() as $ar){
			$permissions[] = intval($ar['id']);
		}

		return $permissions;
	}
}

?>