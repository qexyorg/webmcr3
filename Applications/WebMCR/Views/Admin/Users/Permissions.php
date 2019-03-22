<?php

namespace App\WebMCR\Views\Admin\Users;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use Framework\Components\Alerts\Alerts;
use App\WebMCR\Models\Admin\Users\Permissions as PermissionsModel;
use Framework\Components\Permissions\Permissions as Perm;
use Framework\Components\Secure\CSRF;

class Permissions extends View {

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Perm::equal('admin_permissions_index', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new PermissionsModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Admin/Users/Permissions/tpl/index.tpl', [
			'pagename' => 'Привилегии | Панель управления',
			'list' => $model->getList($params),
			'pagination' => $pagination,
			'count' => $model->getCount($params),
		]);

		exit;
	}

	public function addItem(){
		$config = RouterHelper::getAppConfig();

		if(!Perm::equal('admin_permissions_add', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new PermissionsModel();

		echo $this->getTemplater()->render('Resources/Admin/Users/Permissions/Add/tpl/index.tpl', [
			'pagename' => 'Добавление | Привилегии | Панель управления',
			'types' => $model->getTypes(),
		]);

		exit;
	}

	public function addItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Perm::equal('admin_permissions_add', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new PermissionsModel();

		$add = $model->addSubmit($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($add['text'], $add['title'], $add['type'], $add)
			->execute();
	}

	public function editItem(){
		$config = RouterHelper::getAppConfig();

		if(!Perm::equal('admin_permissions_edit', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect("{$config['meta']['site_url']}admin/permissions/")->execute();
		}

		$model = new PermissionsModel();

		$item = $model->getItem(RouterHelper::getParam('id'));

		if(is_null($item)){
			Alerts::set()->message('Страница не найдена', 'Ошибка 404')
				->redirect("{$config['meta']['site_url']}admin/permissions/")->execute();
		}

		echo $this->getTemplater()->render('Resources/Admin/Users/Permissions/Edit/tpl/index.tpl', [
			'pagename' => 'Редактирование | Привилегии | Панель управления',
			'item' => $item,
			'types' => $model->getTypes(),
		]);

		exit;
	}

	public function editItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Perm::equal('admin_permissions_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new PermissionsModel();

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

		if(!Perm::equal('admin_permissions_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new PermissionsModel();

		$id = intval(RouterHelper::getParam('id'));

		$permission = $model->getItem($id);

		if(is_null($permission)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранная привилегия недоступна', '404')
				->execute();
		}

		if(intval($permission['system'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Удаление системной привилегии недопустимо', 'Системная ошибка')
				->execute();
		}

		if(!$model->remove($id)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления привилегии', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Привилегия была успешно удалена', 'Поздравляем!', true)
			->execute();
	}
}

?>