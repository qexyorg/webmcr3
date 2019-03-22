<?php

namespace App\WebMCR\Views;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Statics as StaticsModel;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;

class Statics extends View {

	public function index(){

		$router = RouterHelper::getCurrent();

		$config = RouterHelper::getAppConfig();

		$route = mb_substr($router['pattern'], mb_strlen($config['meta']['site_url'], 'UTF-8'), null, 'UTF-8');

		$model = new StaticsModel();

		$page = $model->getPage($route);

		if(is_null($page)){
			Alerts::set()->message('Страница не найдена', '404')
				->redirect($config['meta']['site_url'])->execute();
		}

		if(!empty($page['permission']) && !Permissions::equal($page['permission'], true)){
			Alerts::set()->message('У Вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		echo $this->getTemplater()->createTemplate($page['text'])->render([
			'pagename' => $page['title'],
		]);
	}
}

?>