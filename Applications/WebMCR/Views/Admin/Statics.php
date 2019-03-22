<?php

namespace App\WebMCR\Views\Admin;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Admin\Statics as StaticsModel;
use Framework\Components\Secure\CSRF;

class Statics extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_statics_index', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new StaticsModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Admin/Statics/tpl/index.tpl', [
			'pagename' => 'Статические страницы | Панель управления',
			'list' => $model->getList($params),
			'pagination' => $pagination,
			'count' => $model->getCount($params),
		]);

		exit;
	}

	public function addItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_statics_add', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$_user = $this->getUser();

		echo $this->getTemplater()->render('Resources/Admin/Statics/Add/tpl/index.tpl', [
			'pagename' => 'Добавление | Статические страницы | Панель управления',
			'permissions' => $_user->getGroupPermissions(0, true, $_user::USER_COMPLETE_BY_NAME_FULL),
			'template' => $this->getView('/Resources/Admin/Statics/Add/tpl/default-template.tpl')
		]);

		exit;
	}

	public function addItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_statics_add', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new StaticsModel();

		$add = $model->addSubmit($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($add['text'], $add['title'], $add['type'], $add)
			->execute();
	}

	public function editItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_statics_edit', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect("{$config['meta']['site_url']}admin/statics/")->execute();
		}

		$model = new StaticsModel();

		$item = $model->getItem(RouterHelper::getParam('id'));

		if(is_null($item)){
			Alerts::set()->message('Страница не найдена', 'Ошибка 404')
				->redirect("{$config['meta']['site_url']}admin/statics/")->execute();
		}

		$_user = $this->getUser();

		echo $this->getTemplater()->render('Resources/Admin/Statics/Edit/tpl/index.tpl', [
			'pagename' => 'Редактирование | Статические страницы | Панель управления',
			'permissions' => $_user->getGroupPermissions(0, true, $_user::USER_COMPLETE_BY_NAME_FULL),
			'item' => $item
		]);

		exit;
	}

	public function editItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_statics_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new StaticsModel();

		$edit = $model->editSubmit(RouterHelper::getParam('id'), $_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($edit['text'], $edit['title'], $edit['type'], $edit)
			->execute();
	}

	public function removeItem(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_statics_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new StaticsModel();

		if(!$model->remove(RouterHelper::getParam('id'))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления статической страницы', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Статическая страница была успешно удалена', 'Поздравляем!', true)
			->execute();
	}

	public function publicItem(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_statics_public', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new StaticsModel();

		$value = (intval(RouterHelper::getParam('value'))==1) ? 1 : 0;

		if(!$model->publish(RouterHelper::getParam('id'), $value)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка изменения статуса статической страницы', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Статус статической страницы успешно изменен', 'Поздравляем!', true, [
				'value' => $value
			])
			->execute();
	}
}

?>