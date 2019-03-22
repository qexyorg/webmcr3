<?php

namespace App\WebMCR\Views\Admin;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Logger;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Admin\Logs as LogsModel;
use Framework\Components\Secure\CSRF;

class Logs extends View {

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_logs_index', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new LogsModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Admin/Logs/tpl/index.tpl', [
			'pagename' => 'Логи действий | Панель управления',
			'list' => $model->getList($params),
			'pagination' => $pagination,
			'count' => $model->getCount($params),
		]);

		exit;
	}

	public function removeItem(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_logs_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new LogsModel();

		if(!$model->remove(RouterHelper::getParam('id'))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления лога действий', 'Ошибка')
				->execute();
		}
		Logger::base('[ПУ] Удаление лога', "Удаление лога действия в панели управления", __METHOD__, 'admin_logs_remove');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Лог действий был успешно удален', 'Поздравляем!', true)
			->execute();
	}
}

?>