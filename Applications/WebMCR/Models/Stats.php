<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;

class Stats {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function getUserStats($user_id=null){
		$user_id = (is_null($user_id)) ? $this->getUser()->getID() : intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`us`.*'])
			->from(['us' => 'user_stats'])
			->where(["`us`.`user_id`='?'"], [$user_id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $user_id], []);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $user_id], []);
		}

		return Cache::setOnce([__METHOD__, $user_id], $ar[0]);
	}
}

?>