<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use App\WebMCR\Models\User\UserHelper;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Pagination\Pagination;

class News {

	private function getTagByName($name){
		$cache = Cache::getOnce([__METHOD__, $name]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['*'])
			->from('news_tags')
			->where(["`name`='?'"], [$name]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $name], false);
		}

		$tag = $select->getAssoc();

		if(empty($tag)){
			return Cache::setOnce([__METHOD__, $name], false);
		}

		return Cache::setOnce([__METHOD__, $name], $tag[0]);
	}

	private function getNewsIDsByTag($tags){

		$cache = Cache::getOnce([__METHOD__, $tags]);
		if(!is_null($cache)){ return $cache; }

		$tag = $this->getTagByName($tags);

		$result = [];

		if($tag===false){
			return Cache::setOnce([__METHOD__, $tags], $result);
		}

		$tag_id = intval($tag['id']);

		$select = Database::select()
			->columns(['`new_id`'])
			->from('news_tag_links')
			->where(["`tag_id`='?'"], [$tag_id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $tags], $result);
		}

		foreach($select->getAssoc() as $ar){
			$result[] = intval($ar['new_id']);
		}

		return Cache::setOnce([__METHOD__, $tags], $result);
	}

	public function getNewsCount($params=[]){
		$cache = Cache::getOnce([__METHOD__, $params]);
		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$where = ["`status`='?'"];
		$values = [1];

		if(isset($params['tags'])){
			$tags = $this->getNewsIDsByTag($params['tags']);

			if(!empty($tags)){
				$tags = implode(',', $tags);

				$where[] = "`id` IN ({$tags})";
			}
		}

		if(isset($params['search']) && !empty($params['search'])){
			$where[] = "(`title` LIKE '%?%' OR `text_bb` LIKE '%?%')";
			$values[] = $params['search'];
			$values[] = $params['search'];
		}

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('news')
			->where($where, $values);

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

	public function getPagination($params=[]){

		$cache = Cache::getOnce([__METHOD__, $params]);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$url = "{$config['meta']['site_url']}news/page-{PAGE}";

		if(isset($params['tags'])){
			$url = "{$config['meta']['site_url']}news/tags-{$params['tags']}/page-{PAGE}";
		}

		if(isset($params['search']) && !empty($params['search'])){
			$url = "{$config['meta']['site_url']}news/search-{$params['search']}/page-{PAGE}";
		}

		$pagination->setCount($this->getNewsCount($params))
			->setCurrentPage(@$params['page_id'])
			->setLimit($config['pagination']['news']['list'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($url);

		return Cache::setOnce([__METHOD__, $params], $pagination);
	}

	public function getNews($params=[]){

		$pagination = $this->getPagination($params);

		$result = [];

		$user_id = $this->getUser()->getID();

		$where = ["`n`.`status`='?'"];
		$values = [1];

		if(isset($params['tags'])){
			$tags = $this->getNewsIDsByTag($params['tags']);

			if(!empty($tags)){
				$tags = implode(',', $tags);

				$where[] = "`n`.`id` IN ({$tags})";
			}
		}

		if(isset($params['search']) && !empty($params['search'])){
			$where[] = "(`n`.`title` LIKE '%?%' OR `n`.`text_bb` LIKE '%?%')";
			$values[] = $params['search'];
			$values[] = $params['search'];
		}

		$select = Database::select()
			->columns(['`n`.`id`', '`n`.`title`', '`n`.`name`', '`n`.`text_short_html`',
				'`n`.`image`', '`n`.`status`',
				'`n`.`date_create`', '`n`.`date_update`',
				'`n`.`user_id_create`' ,'`n`.`user_id_update`',
				'`uc`.`login`' => '`user_login_create`',
				'`uu`.`login`' => '`user_login_update`',
				'COUNT(DISTINCT `nl`.`id`)' => 'likes',
				'COUNT(DISTINCT `c`.`id`)' => 'comments',
				'COUNT(DISTINCT `nv`.`id`)' => 'views',
				'`nls`.`date`' => '`is_liked`',
				'`nvs`.`date`' => '`is_view`'])
			->from(['n' => 'news'])
			->leftjoin('news_likes', 'nl', ["`nl`.`new_id`=`n`.`id`"])
			->leftjoin('comments', 'c', ["`c`.`value`=`n`.`id`", "`c`.`type`='?'"], ['news'])
			->leftjoin('news_views', 'nv', ["`nv`.`new_id`=`n`.`id`"])
			->leftjoin('news_likes', 'nls', ["`nls`.`new_id`=`n`.`id`", "`nls`.`user_id`='?'"], [$user_id])
			->leftjoin('news_views', 'nvs', ["`nvs`.`new_id`=`n`.`id`", "(`nvs`.`user_id`='?' OR `nvs`.`ip`='?')"], [$user_id, UserHelper::getIP()])
			->where($where, $values)
			->order(['`n`.`id`' => 'DESC'])
			->group(['`n`.`id`'])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		$select->leftjoin('users', 'uc', ["`uc`.`id`=`n`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`n`.`user_id_update`"]);

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		return $select->getAssoc();
	}

	public function getLastNews($count=5){

		$result = [];

		$select = Database::select()
			->columns(['`n`.`id`', '`n`.`title`', '`n`.`name`', '`n`.`status`'])
			->from(['n' => 'news'])
			->order(['`n`.`id`' => 'DESC'])
			->group(['`n`.`id`'])
			->limit($count);

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		return $select->getAssoc();
	}

	public function getNewsByID($id){

		$cache = Cache::getOnce([__METHOD__, $id]);

		if(!is_null($cache)){ return $cache; }

		$user_id = $this->getUser()->getID();

		$select = Database::select()
			->columns(['`n`.*',
				'`uc`.`login`' => '`user_login_create`',
				'`uu`.`login`' => '`user_login_update`',
				'COUNT(DISTINCT `nl`.`id`)' => 'likes',
				'COUNT(DISTINCT `c`.`id`)' => 'comments',
				'COUNT(DISTINCT `nv`.`id`)' => 'views',
				'`nls`.`date`' => '`is_liked`',
				'`nvs`.`date`' => '`is_view`'])
			->from(['n' => 'news'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`n`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`n`.`user_id_update`"])
			->leftjoin('news_likes', 'nl', ["`nl`.`new_id`=`n`.`id`"])
			->leftjoin('comments', 'c', ["`c`.`value`=`n`.`id`", "`c`.`type`='?'"], ['news'])
			->leftjoin('news_likes', 'nls', ["`nls`.`new_id`=`n`.`id`", "`nls`.`user_id`='?'"], [$user_id])
			->leftjoin('news_views', 'nv', ["`nv`.`new_id`=`n`.`id`"])
			->leftjoin('news_views', 'nvs', ["`nvs`.`new_id`=`n`.`id`", "(`nvs`.`user_id`='?' OR `nvs`.`ip`='?')"], [$user_id, UserHelper::getIP()])
			->where(["`n`.`id`='?'"], [$id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $id], null);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){ return Cache::setOnce([__METHOD__, $id], null); }

		return Cache::setOnce([__METHOD__, $id], $ar[0]);
	}

	public function newExists($id){
		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('news')
			->where(["`id`='?'", "`status`!='?'"], [$id, 0]);

		if(!$select->execute()){
			return false;
		}

		$array = $select->getArray();

		if(empty($array) || !isset($array[0][0])){
			return false;
		}

		return (intval($array[0][0])<=0) ? false : true;
	}

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function isLikedNews($new_id){
		$new_id = intval($new_id);

		$cache = Cache::getOnce([__METHOD__, $new_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('news_likes')
			->where(["`new_id`='?'", "`user_id`='?'"], [$new_id, $this->getUser()->getID()])
			->limit(1);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $new_id], false);
		}

		$ar = $select->getArray();

		return Cache::setOnce([__METHOD__, $new_id], (!empty(($ar)) && intval($ar[0][0])>0));
	}

	public function newsLikeJson($new_id){

		$liked = $this->isLikedNews($new_id);

		$user_id = $this->getUser()->getID();

		if($liked){
			$action = Database::delete()
				->from('news_likes')
				->where(["`new_id`='?'", "`user_id`='?'"], [$new_id, $user_id]);
		}else{
			$action = Database::insert()
				->into('news_likes')
				->columns(['new_id', 'user_id', 'date'])
				->values([$new_id, $this->getUser()->getID(), time()]);
		}

		if(!$action->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка выставления симпатий #'.__LINE__
			];
		}

		$this->getUser()->updateStats(['`likes`'], $user_id);

		if($liked){
			Logger::base('Разонравилась новость', "Новость #{$new_id} перестала нравится :c", __METHOD__, 'news_unlike');
		}else{
			Logger::base('Понравилась новость', "Новость #{$new_id} понравилась пользователю", __METHOD__, 'news_like');
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Оценка материала произведена',
			'isLiked' => !$liked,
		];
	}

	public function isView($new_id){
		$new_id = intval($new_id);

		$cache = Cache::getOnce([__METHOD__, $new_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('news_views')
			->where(["`new_id`='?'", "(`user_id`='?' OR `ip`='?')"], [$new_id, $this->getUser()->getID(), UserHelper::getIP()])
			->limit(1);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $new_id], false);
		}

		$ar = $select->getArray();

		return Cache::setOnce([__METHOD__, $new_id], (!empty(($ar)) && intval($ar[0][0])>0));
	}

	public function updateViews($new_id){
		$isView = $this->isView($new_id);

		if(!$isView){
			$action = Database::insert()
				->into('news_views')
				->columns(['new_id', 'user_id', 'ip', 'date'])
				->values([$new_id, $this->getUser()->getID(), UserHelper::getIP(), time()]);

			return $action->execute();
		}

		return false;
	}

	public function getTags(){
		$cache = Cache::get(__METHOD__);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`id`', '`name`', '`title`', '`text`'])
			->from('news_tags');

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::set(__METHOD__, []);
		}

		return Cache::set(__METHOD__, $select->getAssoc());
	}

	public function getTagsByNewsID($new_id){
		$new_id = intval($new_id);

		$cache = Cache::get([__METHOD__, $new_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`nt`.*'])
			->from(['ntl' => 'news_tag_links'])
			->leftjoin('news_tags', 'nt', ["`nt`.`id`=`ntl`.`tag_id`"])
			->where(["`ntl`.`new_id`='?'"], [$new_id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::set([__METHOD__, $new_id], []);
		}

		return Cache::set([__METHOD__, $new_id], $select->getAssoc());
	}
}

?>