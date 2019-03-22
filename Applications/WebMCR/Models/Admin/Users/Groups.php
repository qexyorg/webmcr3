<?php

namespace App\WebMCR\Models\Admin\Users;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\Logger;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Filters\_Input;
use Framework\Components\Filters\_String;
use Framework\Components\Pagination\Pagination;

class Groups {

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
			->from('user_groups');

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
			->setLimit($config['pagination']['admin']['user_groups'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'admin/groups/page-{PAGE}');

		return Cache::setOnce(__METHOD__, $pagination);
	}

	public function getList($params){

		$pagination = $this->getPagination($params);

		$result = [];

		$select = Database::select()
			->columns(['`ug`.*',
				'COUNT(`uig`.`id`)' => '`users_in_group`',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['ug' => 'user_groups'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`ug`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`ug`.`user_id_update`"])
			->leftjoin('users', 'uig', ["`uig`.`group_id`=`ug`.`id`"])
			->group(['`ug`.`id`'])
			->order(['`ug`.`id`' => 'DESC'])
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
			->columns(['`ug`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['ug' => 'user_groups'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`ug`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`ug`.`user_id_update`"])
			->where(["`ug`.`id`='?'"], [$id]);

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
			->from('user_groups')
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

		$config = RouterHelper::getAppConfig();

		$update = Database::update()
			->table('users')
			->set(["`group_id`" => $config['changegroup']['remove']])
			->where(["`group_id`='?'"], [$id]);

		if(!$update->execute()){
			return false;
		}

		if(!$this->removePermissionLinksByGroup($id)){
			return false;
		}

		$delete = Database::delete()
			->from('user_groups')
			->where(["`id`='?'"], [$id]);

		Logger::base('[ПУ] Удаление группы', "Было произведено удаление группы пользователей #{$id}", __METHOD__, 'admin_groups_remove');

		return $delete->execute();
	}

	public function filterPermissionsByTypes($permissions){

		$result = [];

		if(empty($permissions)){
			return $result;
		}

		$ids = implode(',', array_keys($permissions));

		$select = Database::select()
			->columns(['*'])
			->from('permissions')
			->where(["`id` IN ($ids)"]);

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		foreach($select->getAssoc() as $ar){

			$id = intval($ar['id']);

			$value = (isset($permissions[$id])) ? $permissions[$id] : $ar['default'];

			switch($ar['type']){
				case 'integer': $result[$id] = intval($value); break;
				case 'float': $result[$id] = floatval($value); break;
				case 'boolean': $result[$id] = ($value=='true' || intval($value)) ? 'true' : 'false'; break;

				default: $result[$id] = strval($value); break;
			}
		}

		return $result;
	}

	public function addSubmit($params){

		$filter = new _Input($params);

		$filter->add('title', _Input::TYPE_STRING, 1, 32, true)
			->add('name', _Input::TYPE_STRING, 1, 32)
			->add('text', _Input::TYPE_STRING, 1, 255)
			->add('permissions', _Input::TYPE_STRING_ARRAY, 1, 64);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия группы'
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

		$id = $this->insertGroup($data['title'], $data['name'], $data['text']);

		if(!$id){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		$data['permissions'] = $this->filterPermissionsByTypes($data['permissions']);

		if(!$this->insertPermissionLinks($id, $data['permissions'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка создания ссылок привилегий'
			];
		}

		Logger::base('[ПУ] Добавление группы', "Было произведено добавление группы пользователей #{$id}", __METHOD__, 'admin_groups_add');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Группа успешно добавлена',
			'id' => $id
		];
	}

	public function insertPermissionLinks($group_id, $permissions){
		$values = [];

		$time = time();
		$user_id = $this->getUser()->getID();

		foreach($permissions as $k => $v){
			$values[] = [$group_id, $k, $v, $user_id, $user_id, $time, $time];
		}

		$insert = Database::insert()
			->into('group_permissions')
			->columns(['group_id', 'permission_id', 'value', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values($values);

		return $insert->execute();
	}

	public function insertGroup($title, $name, $text){

		$user_id = $this->getUser()->getID();
		$time = time();

		$insert = Database::insert()
			->into('user_groups')
			->columns(['name', 'title', 'text', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$name, $title, $text, $user_id, $user_id, $time, $time]);

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
			->add('text', _Input::TYPE_STRING, 1, 255)
			->add('permissions', _Input::TYPE_STRING_ARRAY, 1, 64);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия группы'
			];
		}

		if(empty($data['name'])){
			$data['name'] = _String::toLatin($data['title']);
		}

		if($item['name'] != $data['name']){
			if(empty($data['name'])){
				$data['name'] = _String::toLatin($data['title']);
			}

			if($this->existsByName($data['name'])){
				return ['type' => false, 'title' => 'Внимание!',
					'text' => 'Уникальное имя уже используется'
				];
			}
		}

		if(!$this->updateGroup($item_id, $data['title'], $data['name'], $data['text'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		if(!$this->removePermissionLinksByGroup($item_id)){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка удаления устаревших ссылок привилегий'
			];
		}

		$data['permissions'] = $this->filterPermissionsByTypes($data['permissions']);

		if(!$this->insertPermissionLinks($item_id, $data['permissions'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка создания ссылок привилегий'
			];
		}

		Logger::base('[ПУ] Изменение группы', "Было произведено изменение группы пользователей #{$item_id}", __METHOD__, 'admin_groups_edit');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Группа успешно сохранена',
		];
	}

	public function removePermissionLinksByGroup($group_id){
		$group_id = intval($group_id);

		$delete = Database::delete()
			->from('group_permissions')
			->where(["`group_id`='?'"], [$group_id]);

		return $delete->execute();
	}

	public function updateGroup($id, $title, $name, $text){
		$id = intval($id);

		$user_id = $this->getUser()->getID();
		$time = time();

		$update = Database::update()
			->table('user_groups')
			->set(['`title`' => $title, '`name`' => $name, '`text`' => $text,
					'`user_id_update`' => $user_id, '`date_update`' => $time])
			->where(["`id`='?'"], [$id]);

		return $update->execute();
	}

	public function getAll(){
		$select = Database::select()
			->columns(['*'])
			->from('user_groups')
			->order(['title' => 'ASC']);

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		return $select->getAssoc();
	}

	public function existsByID($group_id){

		$group_id = intval($group_id);

		$cache = Cache::getOnce([__METHOD__, $group_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('user_groups')
			->where(["`id`='?'"], [$group_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $group_id], false);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $group_id], false);
		}

		return Cache::setOnce([__METHOD__, $group_id], (intval(@$ar[0][0])>0));
	}
}

?>