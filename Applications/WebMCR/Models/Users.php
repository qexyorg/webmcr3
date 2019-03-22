<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use App\WebMCR\Models\User\UserHelper;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Pagination\Pagination;

class Users {

	public function getListCount($params){
		$cache = Cache::getOnce([__METHOD__, $params]);

		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('users');

		$search = (isset($params['search'])) ? $params['search'] : '';

		if(preg_match('/^[a-z0-9_\.]{1,}$/i', $search)){
			$select->where(["`login` LIKE '%?%'"], [$search]);
		}

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

	public function getListPagination($params){

		$cache = Cache::getOnce([__METHOD__, $params]);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$page = 'users/page-{PAGE}';

		$search = (isset($params['search'])) ? $params['search'] : '';

		if(preg_match('/^[a-z0-9_\.]{1,}$/i', $search)){
			$page = "users/search/{$search}/page-{PAGE}";
		}

		$pagination->setCount($this->getListCount($params))
			->setCurrentPage(@$params['page_id'])
			->setLimit($config['pagination']['users']['list'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].$page);

		return Cache::setOnce([__METHOD__, $params], $pagination);
	}

	public function getList($params){

		$pagination = $this->getListPagination($params);

		$result = [];

		$select = Database::select()
			->columns(['`u`.`id`', '`u`.`group_id`', '`u`.`login`', '`u`.`gender`', '`u`.`firstname`', '`u`.`lastname`',
						'`u`.`avatar`', '`u`.`birthday`', '`u`.`about`', '`u`.`date_create`', '`u`.`date_update`',
				'`ug`.`title`' => '`group_title`', '`ug`.`text`' => '`group_text`',
				'`us`.`subscribers`', '`us`.`likes`', '`us`.`comments`'])
			->from(['u' => 'users'])
			->leftjoin('user_groups', 'ug', ['`ug`.`id`=`u`.`group_id`'])
			->leftjoin('user_stats', 'us', ["`us`.`user_id`=`u`.`id`"])
			->order(['`u`.`id`' => 'ASC'])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		$search = (isset($params['search'])) ? $params['search'] : '';

		if(preg_match('/^[a-z0-9_\.]{1,}$/i', $search)){
			$select->where(["`u`.`login` LIKE '%?%'"], [$search]);
		}

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

		if($liked){
			$action = Database::delete()
				->from('news_likes')
				->where(["`new_id`='?'", "`user_id`='?'"], [$new_id, $this->getUser()->getID()]);
		}else{
			$action = Database::insert()
				->into('news_likes')
				->columns(['new_id', 'user_id', 'date'])
				->values([$new_id, $this->getUser()->getID(), time()]);
		}

		if(!$action->execute()){
			return [
				'type' => false,
				'text' => 'Произошла ошибка выставления симпатий #'.__LINE__
			];
		}

		return [
			'type' => true,
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
}

?>