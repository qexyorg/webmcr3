<?php

namespace App\WebMCR\Views\Admin\Users;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Admin\Users\Groups;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Admin\Users\Users as UsersModel;
use Framework\Components\Secure\CSRF;

class Users extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_users_index', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new UsersModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Admin/Users/tpl/index.tpl', [
			'pagename' => 'Пользователи | Панель управления',
			'list' => $model->getList($params),
			'pagination' => $pagination,
			'count' => $model->getCount($params),
		]);

		exit;
	}

	public function editItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_users_edit', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect("{$config['meta']['site_url']}admin/users/")->execute();
		}

		$model = new UsersModel();

		$id = intval(RouterHelper::getParam('id'));

		$item = $model->getItem($id);

		if(is_null($item)){
			Alerts::set()->message('Страница не найдена', 'Ошибка 404')
				->redirect("{$config['meta']['site_url']}admin/users/")->execute();
		}

		$groupsModel = new Groups();

		$_user = $this->getUser();

		echo $this->getTemplater()->render('Resources/Admin/Users/Edit/tpl/index.tpl', [
			'pagename' => 'Редактирование | Пользователи | Панель управления',
			'item' => $item,
			'groups' => $groupsModel->getAll(),
			'permissions' => $_user->getUserPermissions($id, false, $_user::USER_COMPLETE_BY_NAME_FULL),
			'links' => $_user->getUserPermissionLinks($id, false, $_user::USER_COMPLETE_BY_NAME_FULL)
		]);

		exit;
	}

	public function editItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_users_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new UsersModel();

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

		if(!Permissions::equal('admin_users_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new UsersModel();

		if(!$model->remove(RouterHelper::getParam('id'))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления пользователя', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Пользователь был успешно удален', 'Поздравляем!', true)
			->execute();
	}

	public function banItem(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_users_ban', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new UsersModel();

		$value = (intval(RouterHelper::getParam('value'))==1) ? 1 : 0;

		if(!$model->ban(RouterHelper::getParam('id'), $value)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка изменения статуса пользователя', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Пользователь был успешно забанен', 'Поздравляем!', true, [
				'value' => $value
			])
			->execute();
	}

	public function banipItem(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_users_banip', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new UsersModel();

		$banip = $model->banip($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($banip['text'], $banip['title'], $banip['type'])
			->execute();
	}

	public function uploadAvatar(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_users_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new UsersModel();

		$upload = $model->uploadAvatar($_FILES);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($upload['text'], $upload['title'], $upload['type'], $upload)
			->execute();
	}
}

?>