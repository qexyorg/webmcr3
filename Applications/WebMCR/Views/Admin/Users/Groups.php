<?php

namespace App\WebMCR\Views\Admin\Users;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Admin\Users\Groups as GroupsModel;
use App\WebMCR\Models\Admin\Users\Permissions as PermissionsModel;
use Framework\Components\Secure\CSRF;

class Groups extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_groups_index', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new GroupsModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Admin/Users/Groups/tpl/index.tpl', [
			'pagename' => 'Группы пользователей | Панель управления',
			'list' => $model->getList($params),
			'pagination' => $pagination,
			'count' => $model->getCount($params),
		]);

		exit;
	}

	public function addItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_groups_add', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$permissions = new PermissionsModel();

		echo $this->getTemplater()->render('Resources/Admin/Users/Groups/Add/tpl/index.tpl', [
			'pagename' => 'Добавление | Группы пользователей | Панель управления',
			'permissions' => $permissions->getAll()
		]);

		exit;
	}

	public function addItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_groups_add', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new GroupsModel();

		$add = $model->addSubmit($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($add['text'], $add['title'], $add['type'], $add)
			->execute();
	}

	public function editItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_groups_edit', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect("{$config['meta']['site_url']}admin/groups/")->execute();
		}

		$model = new GroupsModel();

		$group_id = intval(RouterHelper::getParam('id'));

		$item = $model->getItem($group_id);

		if(is_null($item)){
			Alerts::set()->message('Страница не найдена', 'Ошибка 404')
				->redirect("{$config['meta']['site_url']}admin/groups/")->execute();
		}

		$_user = $this->getUser();

		echo $this->getTemplater()->render('Resources/Admin/Users/Groups/Edit/tpl/index.tpl', [
			'pagename' => 'Редактирование | Группы пользователей | Панель управления',
			'item' => $item,
			'permissions' => $_user->getGroupPermissions($group_id, false, $_user::USER_COMPLETE_BY_ID_FULL)
		]);

		exit;
	}

	public function editItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_news_tags_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new GroupsModel();

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

		if(!Permissions::equal('admin_groups_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new GroupsModel();

		if(!$model->remove(RouterHelper::getParam('id'))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления группы пользователей', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Группа пользователей была успешно удалена', 'Поздравляем!', true)
			->execute();
	}
}

?>