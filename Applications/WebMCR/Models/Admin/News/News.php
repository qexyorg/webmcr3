<?php

namespace App\WebMCR\Models\Admin\News;

use App\WebMCR\Models\Logger;
use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Filters\_BBCodes;
use Framework\Components\Filters\_Input;
use Framework\Components\Filters\_String;
use Framework\Components\Pagination\Pagination;

class News {

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
			->from('news');

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
			->setLimit($config['pagination']['admin']['news'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'admin/news/page-{PAGE}');

		return Cache::setOnce(__METHOD__, $pagination);
	}

	public function getList($params){

		$pagination = $this->getPagination($params);

		$result = [];

		$select = Database::select()
			->columns(['`n`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['n' => 'news'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`n`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`n`.`user_id_update`"])
			->order(['`n`.`id`' => 'DESC'])
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
			->columns(['`n`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['n' => 'news'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`n`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`n`.`user_id_update`"])
			->where(["`n`.`id`='?'"], [$id]);

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

	public function removeTagLinks($id){

		$delete = Database::delete()
			->from('news_tag_links')
			->where(["`new_id`='?'"], [$id]);

		return $delete->execute();
	}

	public function remove($id){
		$id = intval($id);

		if(!$this->removeTagLinks($id)){
			return false;
		}

		$delete = Database::delete()
			->from('news')
			->where(["`id`='?'"], [$id]);

		Logger::base('[ПУ] Удаление новости', "Было произведено удаление новости {$id}", __METHOD__, 'admin_news_remove');

		return $delete->execute();
	}

	public function addSubmit($params){

		$filter = new _Input($params);

		$filter->add('title', _Input::TYPE_STRING, 1, 64, true)
			->add('name', _Input::TYPE_STRING, 1, 64)
			->add('image', _Input::TYPE_STRING, 1, 255)
			->add('text', _Input::TYPE_STRING, 1, 65535)
			->add('text_short', _Input::TYPE_STRING, 1, 65535)
			->add('tags', _Input::TYPE_STRING, 1, 65535);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия новости'
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

		$tagsModel = new Tags();

		$tags = $tagsModel->filterTags($data['tags']);

		$id = $this->insertNew($data['title'], $data['name'], $data['text_short'], $data['text'], $data['image']);

		if(!$id){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		$this->insertNewsTags($id, $tags);

		Logger::base('[ПУ] Добавление новости', "Было произведено добавление новости {$id}", __METHOD__, 'admin_news_add');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Новость успешно создана',
			'id' => $id
		];
	}

	public function insertNewsTags($new_id, $tags){

		if(empty($tags)){ return 0; }

		$values = [];

		foreach($tags as $tag){
			$values[] = [$tag, $new_id];
		}

		$insert = Database::insert()
			->into('news_tag_links')
			->columns(['tag_id', 'new_id'])
			->values($values);

		if(!$insert->execute()){
			return 0;
		}

		return sizeof($tags);
	}

	public function insertNew($title, $name, $text_short, $text, $image, $status=0){

		$user_id = $this->getUser()->getID();
		$time = time();

		$text_bb = $text;
		$text_html = _BBCodes::parse($text_bb);

		$text_short_bb = $text_short;
		$text_short_html = _BBCodes::parse($text_short_bb);

		$insert = Database::insert()
			->into('news')
			->columns(['title', 'name', 'text_short_html', 'text_short_bb', 'text_html', 'text_bb', 'image', 'status',
				'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$title, $name, $text_short_html, $text_short_bb, $text_html, $text_bb, $image, $status,
				$user_id, $user_id, $time, $time]);

		if(!$insert->execute()){
			return false;
		}

		return $insert->getLastID();
	}

	public function getSelectedTags($id){

		$result = [];

		$select = Database::select()
			->columns(['`tag_id`'])
			->from('news_tag_links')
			->where(["`new_id`='?'"], [$id]);

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		foreach($select->getAssoc() as $ar){
			$result[] = intval($ar['tag_id']);
		}

		return $result;
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

		$filter->add('title', _Input::TYPE_STRING, 1, 64, true)
			->add('name', _Input::TYPE_STRING, 1, 64)
			->add('image', _Input::TYPE_STRING, 1, 255)
			->add('text', _Input::TYPE_STRING, 1, 65535)
			->add('text_short', _Input::TYPE_STRING, 1, 65535)
			->add('tags', _Input::TYPE_STRING, 1, 65535);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия новости'
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

		$tagsModel = new Tags();

		$tags = $tagsModel->filterTags($data['tags']);

		if(!$this->updateNew($item_id, $data['title'], $data['name'], $data['text_short'], $data['text'], $data['image'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		$this->removeTagLinks($item_id);

		$this->insertNewsTags($item_id, $tags);

		Logger::base('[ПУ] Изменение новости', "Было произведено изменение новости {$item_id}", __METHOD__, 'admin_news_edit');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Новость успешно сохранена',
		];
	}

	public function updateNew($id, $title, $name, $text_short, $text, $image, $status=null){
		$id = intval($id);

		$user_id = $this->getUser()->getID();
		$time = time();

		$text_bb = $text;
		$text_html = _BBCodes::parse($text_bb);

		$text_short_bb = $text_short;
		$text_short_html = _BBCodes::parse($text_short_bb);

		$set = ['`title`' => $title, '`name`' => $name, '`text_short_html`' => $text_short_html,
			'`text_short_bb`' => $text_short_bb, '`text_html`' => $text_html, '`text_bb`' => $text_bb,
			'`image`' => $image, '`user_id_update`' => $user_id, '`date_update`' => $time];

		if(!is_null($status)){
			$set['`status`'] = intval($status);
		}

		$update = Database::update()
			->table('news')
			->set($set)
			->where(["`id`='?'"], [$id]);

		return $update->execute();
	}

	public function getUserPublicationsCount($user_id=null){
		if(is_null($user_id)){
			$user_id = $this->getUser()->getID();
		}

		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('news')
			->where(["`user_id`='?'", "`status`='?'"], [$user_id, 1]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $user_id], 0);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $user_id], 0);
		}

		return Cache::setOnce([__METHOD__, $user_id], intval(@intval($ar[0][0])));
	}

	public function publish($id, $value=0){
		$id = intval($id);

		$item = $this->getItem($id);

		if(is_null($item)){
			return false;
		}

		$_user = $this->getUser();

		$value = (intval($value)==1) ? 1 : 0;

		$update = Database::update()
			->table('news')
			->set(['`status`' => $value, '`user_id_update`' => $_user->getID(), '`date_update`' => time()])
			->where(["`id`='?'"], [$id]);

		if(!$update->execute()){
			return false;
		}

		if($value){
			Logger::base('[ПУ] Публикация новости', "Была произведена публикация новости {$id}", __METHOD__, 'admin_news_publish');
		}else{
			Logger::base('[ПУ] Скрытие новости', "Было произведено скрытие новости {$id}", __METHOD__, 'admin_news_unpublish');
		}

		$user_id_create = intval($item['user_id_create']);

		if(!$_user->updateStats(['publications' => $this->getUserPublicationsCount($user_id_create)], $user_id_create)){
			return false;
		}

		return true;
	}
}

?>