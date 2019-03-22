<?php

namespace App\WebMCR\Views\Admin;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Comments;
use App\WebMCR\Models\News;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;

class Main extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function index(){

		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$newsModel = new News();
		$commentsModel = new Comments();

		echo $this->getTemplater()->render('Resources/Admin/tpl/index.tpl', [
			'pagename' => 'Панель управления',
			'news' => $newsModel->getNewsCount(),
			'comments' => $commentsModel->getCommentsCount(),
			'users' => $this->getUser()->getUsersCount(),
		]);

		exit;
	}
}

?>