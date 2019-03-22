<?php

namespace App\WebMCR\Views;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Groups;
use App\WebMCR\Models\Stats;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Filters\_String;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Users as UsersModel;
use Framework\Components\Secure\CSRF;

class Users extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	/**
	 * @return UsersModel
	*/
	private function getModel(){
		return (DI::has('UsersModel')) ? DI::get('UsersModel') : DI::set('UsersModel', new UsersModel());
	}

	public function index(){

		if(!Permissions::equal('users', true)){
			Alerts::set()->message('У вас недостаточно прав для просмотра списка пользователей', 'Ошибка доступа!')
				->redirect()->execute();
		}

		$params = RouterHelper::getParams();

		if(isset($params['search']) && !Permissions::equal('users_search', true)){
			Alerts::set()->message('У вас недостаточно прав для поиска пользователей', 'Ошибка доступа!')
				->redirect()->execute();
		}

		$model = $this->getModel();

		$pagination = $model->getListPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Users/tpl/index.tpl', [
			'pagename' => "Пользователи",
			'users' => $model->getList($params),
			'users_num' => $model->getListCount($params),
			'pagination' => $pagination,
			'search' => RouterHelper::getParam('search')
		]);

		exit;
	}

	public function view(){

		if(!Permissions::equal('user', true)){
			Alerts::set()->message('У вас недостаточно прав для просмотра информации о пользователе', 'Ошибка доступа!')
				->redirect()->execute();
		}

		$login = RouterHelper::getParam('login');

		$_user = $this->getUser();

		$user = $_user->getUser($login, 'login');

		if($user===false){
			Alerts::set()->message('Пользователь недоступен', 'Ошибка 404')->redirect()->execute();
		}

		$stats = new Stats();

		$groups = new Groups();

		echo $this->getTemplater()->render('Resources/Users/View/tpl/index.tpl', [
			'pagename' => "$login | Пользователи",
			'user' => $user,
			'stats' => $stats->getUserStats($user['id']),
			'group' => $groups->getGroup($user['group_id'])
		]);

		exit;
	}

	public function autocomplete(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$params = @$_POST;

		$_user = $this->getUser();

		if(!$_user->isAuth()){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Для получения информации о пользователях, необходимо авторизоваться', 'Требуется авторизация!')
				->execute();
		}

		$value = (isset($params['value'])) ? trim($params['value']) : '';

		$list = [];

		if(mb_strlen($value, 'UTF-8')<=2){
			Alerts::set()->logic(Alerts::JSON_LOGIC)->message('Данные успешно получены', 'Успех!', true, [
				'list' => $list
			])->execute();
		}

		if(empty($value)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)->message('Данные успешно получены', 'Успех!', true, [
				'list' => $list
			])->execute();
		}

		$users = $_user->searchUsers($value, 10);

		foreach($users as $user){
			$list[] = [
				'id' => intval($user['id']),
				'value' => _String::toEntities($user['login'])
			];
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)->message('Данные успешно получены', 'Успех!', true, [
			'list' => $list
		])->execute();
	}
}

?>