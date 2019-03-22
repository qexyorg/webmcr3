<?php

namespace App\WebMCR\Views\Admin;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Admin\Users\Groups;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Admin\Settings as SettingsModel;
use Framework\Components\Secure\CSRF;

class Settings extends View {

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_settings', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new SettingsModel();

		$groupsModel = new Groups();

		echo $this->getTemplater()->render('Resources/Admin/Settings/tpl/index.tpl', [
			'pagename' => 'Настройки | Панель управления',
			'themes' => $model->getThemes(),
			'logics' => $model->getLogics(),
			'currentLogic' => $model->getLogicName(),
			'groups' => $groupsModel->getAll(),
		]);

		exit;
	}

	public function save(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_settings', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new SettingsModel();

		$save = $model->save($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($save['text'], $save['title'], $save['type'], $save)
			->execute();
	}
}

?>