<?php

namespace App\WebMCR\Models\Admin\News;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Filters\_Input;
use Framework\Components\Filters\_String;
use Framework\Components\Pagination\Pagination;

class Tags {

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
			->from('news_tags');

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
			->setLimit($config['pagination']['admin']['news_tags'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'admin/news/tags/page-{PAGE}');

		return Cache::setOnce(__METHOD__, $pagination);
	}

	public function getList($params){

		$pagination = $this->getPagination($params);

		$result = [];

		$select = Database::select()
			->columns(['`nt`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['nt' => 'news_tags'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`nt`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`nt`.`user_id_update`"])
			->order(['`nt`.`id`' => 'DESC'])
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
			->columns(['`nt`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['nt' => 'news_tags'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`nt`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`nt`.`user_id_update`"])
			->where(["`nt`.`id`='?'"], [$id]);

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
			->from('news_tags')
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
			->from('news_tags')
			->where(["`id`='?'"], [$id]);

		Logger::base('[ПУ] Удаление тега', "Было произведено удаление тега {$id}", __METHOD__, 'admin_tags_remove');

		return $delete->execute();
	}

	public function addSubmit($params){

		$filter = new _Input($params);

		$filter->add('title', _Input::TYPE_STRING, 1, 32, true)
			->add('name', _Input::TYPE_STRING, 1, 32)
			->add('text', _Input::TYPE_STRING, 1, 255);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия тега'
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

		$id = $this->insertTag($data['title'], $data['name'], $data['text']);

		if(!$id){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		Logger::base('[ПУ] Добавление тега', "Было произведено добавление тега {$id}", __METHOD__, 'admin_tags_add');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Тег новости успешно добавлен',
			'id' => $id
		];
	}

	public function insertTag($title, $name, $text){

		$user_id = $this->getUser()->getID();
		$time = time();

		$insert = Database::insert()
			->into('news_tags')
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
			->add('text', _Input::TYPE_STRING, 1, 255);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия тега'
			];
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

		if(!$this->updateTag($item_id, $data['title'], $data['name'], $data['text'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		Logger::base('[ПУ] Изменение тега', "Было произведено изменение тега {$item_id}", __METHOD__, 'admin_tags_edit');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Тег новости успешно сохранен',
		];
	}

	public function updateTag($id, $title, $name, $text){
		$id = intval($id);

		$user_id = $this->getUser()->getID();
		$time = time();

		$update = Database::update()
			->table('news_tags')
			->set(['`title`' => $title, '`name`' => $name, '`text`' => $text,
					'`user_id_update`' => $user_id, '`date_update`' => $time])
			->where(["`id`='?'"], [$id]);

		return $update->execute();
	}

	public function getAll(){
		$select = Database::select()
			->columns(['*'])
			->from('news_tags')
			->order(['title' => 'ASC']);

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		return $select->getAssoc();
	}

	public function filterTags($tags){
		if(empty($tags)){
			return [];
		}

		$tags = explode(',', $tags);

		$tags = array_map('intval', $tags);

		$tags = array_unique($tags);

		$tags = implode(',', $tags);

		$select = Database::select()
			->columns(['`id`'])
			->from('news_tags')
			->where(["`id` IN (?)"], [$tags]);

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		$tags = [];

		foreach($select->getAssoc() as $ar){
			$tags[] = intval($ar['id']);
		}

		return $tags;
	}
}

?>