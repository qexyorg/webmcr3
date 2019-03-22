<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Pagination\Pagination;

class Activity {

	private $names = [];

	public function setNames($list){
		$this->names = $list;
	}

	public function getNames(){
		return $this->names;
	}

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function getCount($user_id){
		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $user_id]);

		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$where = ["`user_id`='?'"];
		$where_values = [$user_id];

		$names = $this->getNames();

		if(!empty($names)){

			$names = implode(',', Database::filterIn($names));

			$where[] = "`name` IN ($names)";
		}


		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('logs')
			->where($where, $where_values);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $user_id], $result);
		}

		$array = $select->getArray();

		if(empty($array)){
			return Cache::setOnce([__METHOD__, $user_id], $result);
		}

		$result = intval(@$array[0][0]);

		return Cache::setOnce([__METHOD__, $user_id], $result);
	}

	public function getPagination($user_id, $page_id){
		$user_id = intval($user_id);
		$page_id = intval($page_id);

		$cache = Cache::getOnce([__METHOD__, $user_id, $page_id]);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$pagination->setCount($this->getCount($user_id))
			->setCurrentPage($page_id)
			->setLimit($config['pagination']['profile']['activity'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'profile/activity/page-{PAGE}');

		return Cache::setOnce([__METHOD__, $user_id, $page_id], $pagination);
	}

	public function getActivity($user_id, $page_id){
		$user_id = intval($user_id);
		$page_id = intval($page_id);

		$pagination = $this->getPagination($user_id, $page_id);

		$where = ["`l`.`user_id`='?'"];
		$where_values = [$user_id];

		$names = $this->getNames();

		if(!empty($names)){

			$names = implode(',', Database::filterIn($names));

			$where[] = "`l`.`name` IN ($names)";
		}

		$select = Database::select()
			->columns(['`l`.*'])
			->from(['l' => 'logs'])
			->where($where, $where_values)
			->order(['`l`.`id`' => 'DESC'])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		return $select->getAssoc();
	}

	public function getMessage($link_id){
		$link_id = intval($link_id);

		$cache = Cache::getOnce([__METHOD__, $link_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`m`.*',
				'`ml`.`id`' => '`link_id`',
				'`ml`.`is_read`' => '`is_read`',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['ml' => 'message_links'])
			->innerjoin('messages', 'm', ["`m`.`id`=`ml`.`message_id`"])
			->leftjoin('users', 'uc', ["`uc`.`id`=`m`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`m`.`user_id_update`"])
			->where(["`ml`.`id`='?'", "`ml`.`user_id`='?'"], [$link_id, $this->getUser()->getID()]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $link_id], false);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $link_id], false);
		}

		return Cache::setOnce([__METHOD__, $link_id], $ar[0]);
	}

	public function remove($message_id, $user_id){
		$message_id = intval($message_id);
		$user_id = intval($user_id);

		$delete = Database::delete()
			->from('message_links')
			->where(["`message_id`='?'", "`user_id`='?'"], [$message_id, $user_id]);

		return $delete->execute();
	}
}

?>