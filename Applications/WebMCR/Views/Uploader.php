<?php

namespace App\WebMCR\Views;

use Framework\Alonity\DI\DI;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Uploader as UploaderModel;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use Framework\Components\Secure\CSRF;

class Uploader extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function index(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$_user = $this->getUser();

		if(!$_user->isAuth() || !Permissions::equal('file_uploader', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$filedata = @$_FILES['file'];

		if(empty($filedata) || !intval(@$filedata['size'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Данные перерданы неверно'.var_export($filedata, true), 'Ошибка доступа!')
				->execute();
		}

		$model = new UploaderModel();

		$upload = $model->upload($filedata);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($upload['text'], $upload['title'], $upload['type'], $upload)
			->execute();
	}
}

?>