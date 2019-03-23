<?php

namespace App\WebMCR\Triggers;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\Triggers\TriggersInterface;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use App\WebMCR\Models\User\UserHelper;

class onBeforeCallApp implements TriggersInterface {

	const BANIP_CACHE_NAME = 'getBannedIPs';

	private function getBannedIPs(){
		$cache = Cache::get(self::BANIP_CACHE_NAME);
		if(!is_null($cache)){ return $cache; }

		$result = [];

		$select = Database::select()
			->columns(['*'])
			->from('user_banip');

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::set(self::BANIP_CACHE_NAME, $result);
		}

		foreach($select->getAssoc() as $ar){
			$result[$ar['ip']] = $ar;
		}

		return Cache::set(self::BANIP_CACHE_NAME, $result);
	}

	public function call($params=null){

		$config = RouterHelper::getAppConfig();

		Database::setOptions([
			'engine' => $config['database']['driver'],
			$config['database']['driver'] => $config['database'],
		]);

		if(!$config['install']){
			$banned = $this->getBannedIPs();

			if(isset($banned[UserHelper::getIP()])){
				$banned = $banned[UserHelper::getIP()];
				exit("<center><h3>Вы были забанены</h3><br><br>Причина: {$banned['reason']}</center>");
			}
		}
	}
}

?>