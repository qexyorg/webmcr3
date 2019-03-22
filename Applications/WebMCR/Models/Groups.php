<?php

namespace App\WebMCR\Models;

use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;

class Groups {
	const TYPE_ID = 'id';
	const TYPE_NAME = 'name';

	const GROUP_ID_CACHE_NAME = 'Models\Groups\getGroup';
	const GROUP_ALL_CACHE_NAME = 'Models\Groups\getGroups';

	const CACHE_PATH = '/tmp/cache/groups';

	public function clearCache(){
		Cache::clear(self::CACHE_PATH);
	}

	public function getGroups(){

		$result = [];

		$cache = Cache::get(self::GROUP_ALL_CACHE_NAME, self::CACHE_PATH);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`id`', '`title`', '`name`', '`text`'])
			->from('user_groups');

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::set(self::GROUP_ALL_CACHE_NAME, $result, null, self::CACHE_PATH);
		}

		foreach($select->getAssoc() as $ar){
			Cache::set([self::GROUP_ID_CACHE_NAME, intval($ar['id']), self::TYPE_ID], $ar, null, self::CACHE_PATH);
			Cache::set([self::GROUP_ID_CACHE_NAME, $ar['name'], self::TYPE_NAME], $ar, null, self::CACHE_PATH);
		}

		return Cache::set(self::GROUP_ALL_CACHE_NAME, $result, null, self::CACHE_PATH);
	}

	public function getGroup($value=1, $type='id'){
		if($type==self::TYPE_ID){
			$value = intval($value);
		}elseif($type==self::TYPE_NAME){
			$value = preg_replace('/[^\w]+/i', '', $value);
		}else{
			return false;
		}

		$type = preg_replace('/[^\w]+/i', '', $type);

		$cache = Cache::get([self::GROUP_ID_CACHE_NAME, $value, $type], self::CACHE_PATH);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`id`', '`title`', '`name`', '`text`'])
			->from('user_groups')
			->where(["`$type`='?'"], [$value]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::set([self::GROUP_ID_CACHE_NAME, $value, $type], false, null, self::CACHE_PATH);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return Cache::set([self::GROUP_ID_CACHE_NAME, $value, $type], false, null, self::CACHE_PATH);
		}

		return Cache::set([self::GROUP_ID_CACHE_NAME, $value, $type], $ar[0], null, self::CACHE_PATH);
	}
}

?>