<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\File\File;
use Framework\Components\Path;
use Framework\Components\Permissions\Permissions;

class Subscribes {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function isSubscribe($type='users', $value=0, $user_id=null){
		if(is_null($user_id)){
			$user_id = $this->getUser()->getID();
		}

		$value = intval($value);

		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $type, $user_id, $value]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('subscribes')
			->where(["`value`='?'", "`type`='?'", "`subscriber_id`='?'"], [$value, $type, $user_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $type, $user_id, $value], false);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $type, $user_id, $value], false);
		}

		return Cache::setOnce([__METHOD__, $type, $user_id, $value], (intval($ar[0][0]>0)));
	}

	public function existModRecord($mod, $id){
		$id = intval($id);

		$mod = $this->getMod($mod);

		if(!$mod['values']){ return true; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from($mod['table'])
			->where(["`id`='?'"], [$id]);

		if(!$select->execute()){
			return false;
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return false;
		}

		return (intval(@$ar[0][0])<=0) ? false : true;
	}

	public function getUserSubscribersCount($user_id=null){
		if(is_null($user_id)){
			$user_id = $this->getUser()->getID();
		}

		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('subscribes')
			->where(["`type`='?'", "`value`='?'"], ['users', $user_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $user_id], 0);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $user_id], 0);
		}

		return Cache::setOnce([__METHOD__, $user_id], intval(@intval($ar[0][0])));
	}

	public function updateSubscribe($type='users', $value=0, $user_id=null){
		$_user = $this->getUser();

		if(is_null($user_id)){
			$user_id = $_user->getID();
		}

		$user_id = intval($user_id);

		$value = intval($value);

		if(!$this->existModRecord($type, $value)){
			return [
				'type' => false,
				'title' => 'Ошибка',
				'text' => 'Запись не найдена'
			];
		}

		$isSubscribe = $this->isSubscribe($type, $value, $user_id);

		if($isSubscribe){
			$action = Database::delete()
				->from('subscribes')
				->where(["`type`='?'", "`value`='?'", "`subscriber_id`='?'"], [$type, $value, $user_id]);
		}else{
			$action = Database::insert()
				->into('subscribes')
				->columns(['value', 'subscriber_id', 'type', 'date'])
				->values([$value, $user_id, $type, time()]);
		}

		if(!$action->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'.$action->getError()
			];
		}

		if($isSubscribe){
			Logger::base('Отписка', "Отмена подписки \"{$type}\"", __METHOD__, 'unsubscribe');
		}else{
			Logger::base('Подписка', "Оформление подписки \"{$type}\"", __METHOD__, 'subscribe');
		}

		if($type=='users'){
			if(!$_user->updateStats(['subscribers' => $this->getUserSubscribersCount($value)], $value)){
				return false;
			}
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => ($isSubscribe) ? 'Вы успешно отписались от обновлений' : 'Подписка успешно оформлена',
			'value' => !$isSubscribe
		];
	}

	public function getStatsMods(){
		$config = RouterHelper::getAppConfig();

		if(!isset($config['subscribe_mods']) || empty($config['subscribe_mods'])){
			return [];
		}

		$result = [];

		foreach($config['subscribe_mods'] as $mod => $ar){
			if(!isset($ar['stats']) || !$ar['stats']){
				continue;
			}

			$result[] = $mod;
		}

		return $result;
	}

	public function modComplete($mod){
		if(!is_array($mod) || empty($mod)){
			return false;
		}

		$array = ['type', 'prefix', 'table', 'values'];

		sort($array);

		$mod = array_keys($mod);

		sort($mod);

		return $mod===$array;
	}

	public function getPermissions($prefix){
		$user = $this->getUser()->getCurrentUser();

		$user['group_id'] = intval($user['group_id']);

		return [
			'subscribe' => Permissions::get("{$prefix}_subscribe", $user['group_id']),
		];
	}

	public function hasMod($name){
		$config = File::config()
			->name('Config')
			->setPath(Path::app())
			->get();

		return isset($config['subscribe_mods'][$name]);
	}

	public function getMod($name){
		$config = File::config()
			->name('Config')
			->setPath(Path::app())
			->get();

		if(!$this->hasMod($name)){
			return null;
		}

		return $config['subscribe_mods'][$name];
	}

	public function setMod($name, $value, $table=null, $prefix=null){
		$config = RouterHelper::getAppConfig();

		if(is_null($prefix)){
			$prefix = $name;
		}

		if(is_null($table)){
			$table = $name;
		}

		$config['subscribe_mods'][$name] = [
			'type' => $name,
			'prefix' => $prefix,
			'table' => $table,
			'values' => (intval($value) > 0),
		];

		$config = File::config()
			->name('Config')
			->setPath(Path::app())
			->setData($config)
			->build();

		return $config->execute();
	}
}

?>