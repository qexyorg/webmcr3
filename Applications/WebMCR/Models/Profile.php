<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Model\Model;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Mail\Mail;
use App\WebMCR\Models\User\User;
use App\WebMCR\Models\User\UserHelper;
use Framework\Components\Database\Database;
use Framework\Components\File\File;
use Framework\Components\Filters\_Input;
use Framework\Components\Path;
use Framework\Components\Permissions\Permissions;

class Profile extends Model {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function authUser($params){

		$login = @$params['login'];
		$password = @$params['password'];
		$remember = (@$params['remember']=='true') ? true : false;

		$_user = $this->getUser();

		if(preg_match("/^[a-z0-9\.]+\@[a-z0-9\-\.]+$/i", $login)){
			$user = $_user->getUserByEmail($login);
		}else{
			$user = $_user->getUserByLogin($login);
		}

		$user_id = intval(@$user['id']);

		$user_ip = UserHelper::getIP();

		Logger::base("Попытка авторизации", "Попытка авторизации под IP адресом {$user_ip}", __METHOD__, 'auth_try', $user_id);

		if($user===false){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Неверный Логин/E-Mail или пароль'
			];
		}

		if(!$_user->checkPassword($password, $user['salt'], $user['password'])){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Неверный Логин/E-Mail или пароль'
			];
		}

		$perms = $_user->getUserPermissions($user_id, true, $_user::USER_COMPLETE_BY_NAME_VALUE);

		if(!@$perms['auth']){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Доступ к авторизации ограничен'
			];
		}

		if(!$_user->setAuth($user['id'], $remember)){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Произошла ошибка авторизации'
			];
		}

		Logger::base("Авторизация", "Пользователь успешно прошел авторизацию", __METHOD__, 'auth', $user['id']);

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Авторизация прошла успешно'
		];
	}

	private function isValidCaptcha($token){

		$config = RouterHelper::getAppConfig();

		if(!$config['register']['captcha']){
			return true;
		}

		$ip = UserHelper::getIP();

		$private = $config['captcha']['recaptcha']['private'];

		$url = "https://www.google.com/recaptcha/api/siteverify?secret={$private}&response={$token}&remoteip={$ip}";

		$call = File::upload()->getFromUrl($url);

		if($call===false){
			return false;
		}

		$json = json_decode($call, true);

		return @$json['success'];
	}

	public function registerUser($params){

		$config = RouterHelper::getAppConfig();

		if(!$config['register']['enable']){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Регистрация отключена администрацией сайта'
			];
		}

		$email = @$params['email'];
		$captcha = @$params['g-recaptcha-response'];

		if(!$this->isValidCaptcha($captcha)){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Проверка не пройдена'
			];
		}

		$_user = $this->getUser();

		if($_user->isAuth()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Вы уже зарегистрированы и не можете сделать это снова'
			];
		}

		if(empty($email)){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Заполните поле E-Mail адреса'
			];
		}

		if(!preg_match("/^[a-z0-9\.]+\@[a-z0-9\-\.]+$/i", $email)){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Неверный формат E-Mail адреса'
			];
		}

		Logger::base("Регистрация", "Попытка регистрации под E-Mail адресом {$email}", __METHOD__, 'register_try');

		$expl = explode('@', $email);

		if(!empty($config['mail']['blacklist'])){
			if(in_array($expl[1], $config['mail']['blacklist'])){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Запрещено использовать выбранный почтовый ящик'
				];
			}
		}

		if(!empty($config['mail']['whitelist'])){
			if(!in_array($expl[1], $config['mail']['whitelist'])){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Запрещено использовать выбранный почтовый ящик'
				];
			}
		}

		if($_user->userExists($email, 'email')){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Запрещено использовать выбранный E-Mail адрес'
			];
		}

		$create = $_user->createUser(['email' => $email]);

		if($create===false){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка создания пользователя'
			];
		}

		$view = new View();

		$send = Mail::send()
			->subject("[{$config['meta']['sitename']}] Регистрация")
			->message($view->getTemplater()->render('Resources/Profile/tpl/mail-register.tpl', [
				'login' => $create['login'],
				'email' => $create['email'],
				'password' => $create['password']
			]))
			->address($email);

		if(!$send->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => $send->getError()
			];
		}

		Logger::base("Регистрация", "Пользователь успешно зарегистрировался", __METHOD__, 'register', $create['user_id']);

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Вы успешно зарегистрировались. Для входа, используйте данные, отправленные на указанный E-Mail адрес'
		];
	}

	public function restorePassword($params){

		$email = @$params['email'];
		$captcha = @$params['g-recaptcha-response'];

		if(!$this->isValidCaptcha($captcha)){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Проверка не пройдена'
			];
		}

		$_user = $this->getUser();

		if($_user->isAuth()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Доступ запрещен'
			];
		}

		if(empty($email)){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Заполните поле E-Mail адреса'
			];
		}

		if(!preg_match("/^[a-z0-9\.]+\@[a-z0-9\-\.]+$/i", $email)){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Неверный формат E-Mail адреса'
			];
		}

		Logger::base("Сброс пароля", "Попытка сброса пароля под E-Mail адресом {$email}", __METHOD__, 'restore_try', $_user->getID());

		$config = RouterHelper::getAppConfig();

		$user = $_user->getUser($email, 'email');

		if($user===false){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Пользователь не найден'
			];
		}

		$token = $_user->createUserToken($user['id']);

		if($token===false){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Произошла ошибка создания токена'
			];
		}

		$view = new View();

		$send = Mail::send()
			->subject("[{$config['meta']['sitename']}] Восстановление доступа")
			->message($view->getTemplater()->render('Resources/Profile/tpl/mail-restore.tpl', [
				'login' => $user['login'],
				'email' => $email,
				'token' => $token
			]))
			->address($email);

		if(!$send->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => $send->getError()
			];
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Инструкция по восстановлению доступа отправлена на указанный E-Mail адрес'
		];
	}

	public function changeAvatar($files){

		if(!isset($files['avatar']) || empty($files['avatar'])){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Изображение не выбрано'
			];
		}

		$extensions = str_replace(' ', '', Permissions::get('profile_avatar_extensions'));
		$extensions = explode(',', $extensions);

		$minSize = explode('x', Permissions::get('profile_avatar_min_size'));
		$maxSize = explode('x', Permissions::get('profile_avatar_max_size'));

		$minSize = array_map('intval', $minSize);
		$maxSize = array_map('intval', $maxSize);

		$size = @getimagesize($files['avatar']['tmp_name']);

		if(!$size){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Неверный формат файла'
			];
		}

		if($size[0]<$minSize[0] || $size[1]<$minSize[1] || $size[0]>$maxSize[0] || $size[1]>$maxSize[1]){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Недопустимое разрешение изображения'
			];
		}

		$upload = File::upload()
			->extensions($extensions)
			->maxFileSize(Permissions::get('profile_avatar_max_filesize'))
			->setUploadPath(Path::to('/tmp'))
			->maxFiles(1)
			->files($files['avatar'])
			->setRandomName(10, 16);

		if(!$upload->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Ошибка загрузки изображения'
			];
		}

		$paths = $upload->getPaths();
		$names = $upload->getNames();

		$expl = explode('.', $names[0]);
		$user = $this->getUser()->getCurrentUser();

		$config = RouterHelper::getAppConfig();

		$filter = File::image()
			->source($paths[0])
			->filename(Path::to("/Public/WebMCR/Uploads/avatars/{$user['login']}.{$expl[1]}"))
			->scale(1)
			->minWidth($minSize[0])
			->minHeight($minSize[1])
			->maxWidth($maxSize[0])
			->maxHeight($maxSize[1]);

		if(!$filter->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Ошибка фильтрации изображения'
			];
		}

		if(!File::removeFiles('/tmp/'.$names[0])){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка обновления аватара'
			];
		}

		$url = "{$config['meta']['site_url']}Uploads/avatars/{$user['login']}.{$expl[1]}";

		if(!$this->getUser()->updateUser(['avatar' => $url])){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка обновления пользователя'
			];
		}

		Logger::base("Изменение автара", "Произведено изменение автара пользователя", __METHOD__, 'profile_change_avatar');

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Аватар успешно изменен',
			'avatar' => $url,
		];
	}

	public function isValidRestoreToken($token){

		$config = RouterHelper::getAppConfig();

		$where = ["`token`='?'"];
		$where_values = [$token];

		if($config['restore_expire']){
			$where[] = "`date_create`+?>'?'";
			$where[] = "`type`='?'";
			$where_values[] = intval($config['restore_expire']);
			$where_values[] = time();
			$where_values[] = 'restore';
		}

		$select = Database::select()
			->columns(['`user_id`'])
			->from('user_tokens')
			->where($where, $where_values);

		if(!$select->execute() || $select->getNum()<=0){
			return false;
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return false;
		}

		return intval(@$ar[0]['user_id']);
	}

	public function clearRestore($user_id){
		$delete = Database::delete()
			->from('user_tokens')
			->where(["`user_id`='?'", "`type`='?'"], [$user_id, 'restore']);

		return ($delete->execute());
	}

	public function restoreComplete($params){

		$_user = $this->getUser();

		if($_user->isAuth()){
			return [
				'type' => false,
				'title' => 'Вы не можете восстановить пароль, будучи авторизованным на сайте',
				'text' => 'Доступ запрещен'
			];
		}

		$filter = new _Input($params);

		$filter->add('newpassword', _Input::TYPE_STRING, 1, 255, true)
			->add('repassword', _Input::TYPE_STRING, 1, 255, true)
			->add('restore', _Input::TYPE_STRING, 32, 32, true);

		if(!$filter->isValid()){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Заполните необходимые поля'
			];
		}

		$data = $filter->filter();

		$user_id = $this->isValidRestoreToken($data['restore']);

		if($user_id===false){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Неверный токен сброса пароля'
			];
		}

		$user = $_user->getUser($user_id);

		if($user===false){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Пользователь не найден'
			];
		}

		if(mb_strlen($data['newpassword'], 'UTF-8')<6){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Пароль должен состоять минимум из 6 символов'
			];
		}

		if($data['newpassword']!==$data['repassword']){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Пароли не совпадают'
			];
		}

		if(!$_user->updateUser([
			'password' => $data['newpassword']
		], $user_id)){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка обновления пользователя'
			];
		}

		$view = new View();

		$config = RouterHelper::getAppConfig();

		$send = Mail::send()
			->subject("[{$config['meta']['sitename']}] Пароль успешно изменен")
			->message($view->getTemplater()->render('Resources/Profile/tpl/mail-restore-complete.tpl', [
				'login' => $user['login'],
				'email' => $user['email'],
				'password' => $data['newpassword']
			]))
			->address($user['email']);

		if(!$send->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => $send->getError()
			];
		}

		if(!$this->clearRestore($user_id)){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Произошла ошибка очистки сбросов токенов. Обратитесь к администрации'
			];
		}

		Logger::base("Сброс пароля", "Сброс пароля произведен успешно", __METHOD__, 'restore', $user_id);

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Пароль успешно изменен'
		];
	}

	private function saveProfileSecurity($json){

		$_user = $this->getUser();

		$user = $_user->getCurrentUser();

		$json = json_encode($json);

		$token = $_user->createUserToken($user['id'], 'mixed', $json);

		if($token===false){
			return false;
		}

		$config = RouterHelper::getAppConfig();

		$view = new View();

		$send = Mail::send()
			->subject("[{$config['meta']['sitename']}] Подтверждение изменения настроек безопасности")
			->message($view->getTemplater()->render('Resources/Profile/Settings/tpl/mail-security.tpl', [
				'login' => $user['login'],
				'email' => $user['email'],
				'token' => $token
			]))
			->address($user['email']);

		return $send->execute();
	}

	public function saveProfileSettings($params){

		if(!$this->getUser()->isAuth() || !Permissions::equal('profile_settings', true)){
			return [
				'type' => false,
				'title' => 'Ошибка доступа!',
				'text' => 'У вас недостаточно прав для доступа к выбранному разделу'
			];
		}

		$filter = new _Input($params);

		$filter->add('firstname', _Input::TYPE_STRING, 1, 64)
			->add('lastname', _Input::TYPE_STRING, 1, 64)
			->add('birthday', _Input::TYPE_STRING, 1, 10)
			->add('login', _Input::TYPE_STRING, 1, 64)
			->add('email', _Input::TYPE_STRING, 1, 128)
			->add('about', _Input::TYPE_STRING, 1, 65536)
			->add('password', _Input::TYPE_STRING, 6, 255);

		if(!$filter->isValid()){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Заполните обязательные поля'
			];
		}

		$data = $filter->filter();

		$birthday = intval(strtotime($data['birthday']));

		$_user = $this->getUser();

		$user = $_user->getCurrentUser();

		$json = [];

		if($user['login']!=$data['login']){
			if($_user->userExists($data['login'], 'login')){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Такой логин уже используется. Используйте другой'
				];
			}

			$json['login'] = $data['login'];
		}

		if($user['email']!=$data['email']){
			if($_user->userExists($data['email'], 'email')){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Такой E-Mail уже используется. Используйте другой'
				];
			}

			$json['email'] = $data['email'];
		}

		if(!empty($data['password']) && !$_user->checkPassword($data['password'], $user['salt'], $user['password'])){

			if(mb_strlen($data['password'], 'UTF-8')<6){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Пароль должен состоять не менее чем из 6 символов'
				];
			}

			$json['password'] = $_user->createPassword($data['password']);
		}

		$security = false;

		if(!empty($json)){
			if(!$this->saveProfileSecurity($json)){
				return [
					'type' => false,
					'title' => 'Внимание',
					'text' => 'Произошла ошибка сохранения настроек безопасности'
				];
			}

			$security = true;
		}

		$fields = [
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'birthday' => $birthday,
			'about' => $data['about']
		];

		if(!$_user->updateUser($fields)){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка обновления пользовательской информации. Обратитесь к администрации'
			];
		}

		Logger::base("Изменение настроек", "Произведено изменение личной информации", __METHOD__, 'profile_settings_change');

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => ($security) ? 'Настройки пользователя успешно сохранены. Подтверждение настроек безопасности было отправлено на Ваш текущий E-Mail адрес' : 'Настройки пользователя успешно сохранены'
		];
	}

	public function settingsSecurityComplete($token){
		$_user = $this->getUser();

		$info = $_user->getUserToken($token);

		if($info===false){
			return [
				'type' => false,
				'text' => 'Неверный токен пользователя. Скорее всего, он устарел',
				'title' => 'Ошибка'
			];
		}

		$user_id = intval($info['user_id']);

		$data = json_decode($info['data'], true);

		$set = [];

		if(isset($data['login'])){
			if($_user->userExists($data['login'], 'login')){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Настройки не могут быть применены, так как измененный логин уже используется'
				];
			}

			$set['login'] = $data['login'];
		}

		if(isset($data['email'])){
			if($_user->userExists($data['email'], 'email')){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Настройки не могут быть применены, так как измененный E-Mail адрес уже используется'
				];
			}

			$set['email'] = $data['email'];
		}

		if(isset($data['password'])){
			$set['password'] = $data['password'];
		}

		if(!$_user->updateUser($set, $user_id, 'id', true) || !$_user->deleteUserToken($user_id, 'mixed')){
			return [
				'type' => false,
				'text' => 'Произошла ошибка обновления настроек',
				'title' => 'Ошибка!'
			];
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Настройки пользователя были успешно применены'
		];
	}
}

?>