<?php

namespace App\WebMCR\Views;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Subscribes as SubscribesModel;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Cache\Cache;
use Framework\Components\Secure\CSRF;

class Subscribes extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	/** @return SubscribesModel */
	private function getSubscribes(){
		if(DI::has('Subscribes')){
			return DI::get('Subscribes');
		}

		return DI::set('Subscribes', new SubscribesModel());
	}

	private function getMod($name){
		$cache = Cache::getOnce([__METHOD__, $name]);
		if(!is_null($cache)){ return $cache; }

		return Cache::setOnce([__METHOD__, $name], $this->getSubscribes()->getMod($name));
	}

	public function update(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$type = RouterHelper::getParam('mod');

		$mod = $this->getMod($type);

		$subscribes = $this->getSubscribes();

		if(!$subscribes->modComplete($mod)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Модуль не укомплектован. Обратитесь к администрации', 'Ошибка')
				->execute();
		}

		$user_id = $this->getUser()->getID();

		$value = intval(RouterHelper::getParam('value'));

		$perms = $subscribes->getPermissions($type);

		if(!$perms['subscribe']){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для подписки на обновления', 'Недостаточно прав')
				->execute();
		}

		$update = $subscribes->updateSubscribe($type, $value, $user_id);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($update['text'], $update['title'], $update['type'], ['value' => $update['value']])
			->execute();
	}
}

?>