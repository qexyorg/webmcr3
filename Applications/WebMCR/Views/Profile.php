<?php

namespace App\WebMCR\Views;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Activity;
use App\WebMCR\Models\Logger;
use App\WebMCR\Models\Messages;
use App\WebMCR\Models\Stats;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Filters\_Input;
use Framework\Components\Filters\_String;
use Framework\Components\Permissions\Permissions;
use Framework\Components\Secure\CSRF;

class Profile extends View {

	/** @return \App\WebMCR\Models\Profile */
	private function getProfileModel(){

		if(DI::has('ProfileModel')){
			return DI::get('ProfileModel');
		}

		return DI::set('ProfileModel', new \App\WebMCR\Models\Profile());
	}

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function auth(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('auth', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Доступ к авторизации ограничен администрацией', 'Доступ запрещен')
				->execute();
		}

		$auth = $this->getProfileModel()->authUser($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $auth['type'], $auth)
			->execute();
	}

	public function register(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$register = $this->getProfileModel()->registerUser($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $register['type'], $register)
			->execute();
	}

	public function index(){

		$_user = $this->getUser();

		$config = RouterHelper::getAppConfig();

		if(!$_user->isAuth()){
			Alerts::set()->redirect($config['meta']['site_url'])
				->message('Для доступа к личному кабинету, необходимо авторизоваться на сайте', 'Доступ запрещен!', false)
				->execute();
		}

		$stats = new Stats();

		echo $this->getTemplater()->render('Resources/Profile/tpl/index.tpl', [
			'pagename' => 'Личный кабинет',
			'stats' => $stats->getUserStats(),
		]);

		exit;
	}

	public function restore(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$restore = $this->getProfileModel()->restorePassword($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $restore['type'], $restore)
			->execute();
	}

	public function restoreComplete(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$restore = $this->getProfileModel()->restoreComplete($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $restore['type'], $restore)
			->execute();
	}

	public function restorePage(){

		$_user = $this->getUser();

		$config = RouterHelper::getAppConfig();

		if($_user->isAuth()){
			Alerts::set()->message('Вы авторизованы и не можете сбросить пароль', 'Внимание!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = $this->getProfileModel();

		$token = RouterHelper::getParam('token');

		$user_id = $model->isValidRestoreToken($token);

		if($user_id===false){
			Alerts::set()->message('Неверный токен сброса пароля', 'Ошибка!')
				->redirect($config['meta']['site_url'])->execute();
		}

		echo $this->getTemplater()->render('Resources/Profile/tpl/restore.tpl', [
			'pagename' => 'Сброс пароля',
			'token' => $token
		]);

		exit;
	}

	public function logout(){

		$_user = $this->getUser();

		$config = RouterHelper::getAppConfig();

		if(!$_user->isAuth()){
			Alerts::set()->message('Вы не авторизованы', 'Внимание!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$user_id = $_user->getID();

		$_user->setUnauth();

		Logger::base("Авторизация", "Пользователь покинул аккаунт", __METHOD__, 'logout', $user_id);

		Alerts::set()->message('Вы успешно вышли из аккаунта', 'Поздравляем!')
			->redirect($config['meta']['site_url'])->execute();
	}

	public function avatarChange(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_avatar_change', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для изменения аватара', 'Недостаточно прав')
				->execute();
		}

		$change = $this->getProfileModel()->changeAvatar($_FILES);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $change['type'], $change)
			->execute();
	}

	public function messages(){

		$_user = $this->getUser();

		$config = RouterHelper::getAppConfig();

		if(!$_user->isAuth()){
			Alerts::set()->message('Для доступа к выбранному разделу, Вам необходимо авторизоваться', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		if(!Permissions::equal('profile_messages_list', true)){
			Alerts::set()->message('У вас недостаточно прав для просмотра сообщений', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$messages = new Messages();

		$page_id = RouterHelper::getParam('page_id');

		$user_id = $_user->getID();

		$pagination = $messages->getPagination($user_id, $page_id)->execute();

		$stats = new Stats();

		echo $this->getTemplater()->render('Resources/Profile/Messages/tpl/index.tpl', [
			'pagename' => 'Сообщения | Личный кабинет',
			'stats' => $stats->getUserStats(),
			'messages' => $messages->getMessages($user_id, $page_id),
			'messages_num' => $messages->getCount($user_id),
			'pagination' => $pagination,
		]);

		exit;
	}

	public function message(){

		$_user = $this->getUser();

		$config = RouterHelper::getAppConfig();

		if(!$_user->isAuth()){
			Alerts::set()->message('Для доступа к выбранному разделу, Вам необходимо авторизоваться', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		if(!Permissions::equal('profile_messages_view', true)){
			Alerts::set()->message('У вас недостаточно прав для просмотра сообщений', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$messages = new Messages();

		$link_id = RouterHelper::getParam('message_link_id');

		$user_id = $_user->getID();

		$message = $messages->getMessageByLink($link_id, $user_id);

		$message_id = intval($message['id']);

		if($message===false){
			Alerts::set()->message('Сообщение недоступно. Возможно, оно еще не создано или уже удалено', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$pagination = $messages->getPaginationReply($message_id)->execute();

		$stats = new Stats();

		echo $this->getTemplater()->render('Resources/Profile/Messages/View/tpl/index.tpl', [
			'pagename' => "{$message['subject']} | Сообщения | Личный кабинет",
			'stats' => $stats->getUserStats(),
			'message' => $message,
			'reply_list' => $messages->getReplyList($message_id),
			'reply_num' => $messages->getCountReply($message_id),
			'reply_pagination' => $pagination,
		]);

		exit;
	}

	public function messageRemove(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_messages_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для удаления сообщения', 'Недостаточно прав!')
				->execute();
		}

		$messages = new Messages();

		$link_id = intval(@$_POST['link_id']);

		$user_id = $this->getUser()->getID();

		$message = $messages->getMessageByLink($link_id, $user_id);

		if($message===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Возможно, оно еще не создано или уже удалено', 'Сообщение недоступно')
				->execute();
		}

		if(!$messages->remove($link_id, $user_id)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления сообщения', 'Ошибка!')
				->execute();
		}

		Logger::base("Удаление беседы", "Беседа #{$link_id} была успешно удалена", __METHOD__, 'profile_message_remove');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Сообщение успешно удалено', 'Поздравляем!', true)
			->execute();
	}

	public function messageReplyAdd(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_messages_reply_add', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для добавления ответов', 'Недостаточно прав!')
				->execute();
		}

		$messages = new Messages();

		$link_id = intval(@$_POST['link_id']);

		$message = $messages->getMessageByLink($link_id, $this->getUser()->getID());

		if($message===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Сообщение недоступно. Возможно, оно еще не создано или уже удалено', 'Ошибка доступа!')
				->execute();
		}

		if(intval($message['is_close'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Беседа закрыта', 'Ошибка доступа!')
				->execute();
		}

		$message_id = intval($message['id']);

		$reply = $messages->replyAdd($message_id, @$_POST['text']);

		if($reply===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка добавления ответа. Обратитесь к администрации', 'Ошибка!')
				->execute();
		}

		if(!$messages->setRead(0, $link_id)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Ответ добавлен, но произошла ошибка обновления статуса сообщения. Обратитесь к администрации', 'Внимание!')
				->execute();
		}

		Logger::base("Добавление ответа в беседе", "Добавление ответа в беседе #{$message_id}", __METHOD__, 'profile_message_reply_add');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Ответ успешно добавлен', 'Поздравляем!', true, [
				'comments_num' => $messages->getCountReply($message_id),
				'comment' => $this->getTemplater()->render('Resources/Profile/Messages/View/tpl/reply-id.tpl', [
					'reply' => $reply
				])
			])
			->execute();
	}

	public function messageReplyQuote(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_messages_reply_view', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для просмотра ответов', 'Недостаточно прав!')
				->execute();
		}

		$messages = new Messages();

		$reply_id = RouterHelper::getParam('reply_id');

		$reply = $messages->getReply($reply_id);

		if($reply===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Доступ к ответу ограничен. Возможно, он был удален', 'Ошибка доступа!')
				->execute();
		}

		$message = $messages->getMessageByID($reply['message_id']);

		if($message===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Сообщение недоступно. Возможно, оно еще не создано или уже удалено', 'Ошибка доступа!')
				->execute();
		}

		if(intval($message['is_close'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Беседа закрыта', 'Ошибка доступа!')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Доступ на цитирование получен', 'Успех!', true, [
				'comment' => _String::toEntities($reply['text_bb'])
			])
			->execute();
	}

	public function messageReplyRemove(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_messages_reply_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для удаления ответов', 'Недостаточно прав!')
				->execute();
		}

		$messages = new Messages();

		$reply_id = RouterHelper::getParam('reply_id');

		$reply = $messages->getReply($reply_id);

		$user_id = $this->getUser()->getID();

		if($reply===false || intval($reply['user_id_create'])!=$user_id){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Доступ к ответу ограничен. Возможно, он был удален', 'Ошибка доступа!')
				->execute();
		}

		$message = $messages->getMessageByID($reply['message_id']);

		if($message===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Сообщение недоступно. Возможно, оно еще не создано или уже удалено', 'Ошибка доступа!')
				->execute();
		}

		if(intval($message['is_close'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Беседа закрыта', 'Ошибка доступа!')
				->execute();
		}

		$remove = $messages->messageReplyRemove($reply_id);

		if(!$remove){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления. Обратитесь к администрации', 'Ошибка!')
				->execute();
		}

		Logger::base("Удаление ответа в беседе", "Удаление ответа в беседе #{$reply['message_id']}", __METHOD__, 'profile_message_reply_remove');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Ответ успешно удален', 'Поздравляем!', true, [
				'num' => $messages->getCountReply($reply['message_id'])
			])
			->execute();
	}

	public function messageReplyEdit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_messages_reply_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для редактирования ответов', 'Недостаточно прав!')
				->execute();
		}

		$messages = new Messages();

		$reply_id = RouterHelper::getParam('reply_id');

		$reply = $messages->getReply($reply_id);

		$user_id = $this->getUser()->getID();

		if($reply===false || intval($reply['user_id_create'])!=$user_id){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Доступ к ответу ограничен. Возможно, он был удален', 'Ошибка доступа!')
				->execute();
		}

		$message = $messages->getMessageByID($reply['message_id']);

		if($message===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Сообщение недоступно. Возможно, оно еще не создано или уже удалено', 'Ошибка доступа!')
				->execute();
		}

		if(intval($message['is_close'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Беседа закрыта', 'Ошибка доступа!')
				->execute();
		}

		Logger::base("Изменение ответа в беседе", "Изменение ответа в беседе #{$reply['message_id']}", __METHOD__, 'profile_message_reply_edit');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Доступ на редактирование получен', 'Успех!', true, [
				'comment' => _String::toEntities($reply['text_bb'])
			])
			->execute();
	}

	public function messageReplySave(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_messages_reply_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для редактирования ответов', 'Недостаточно прав!')
				->execute();
		}

		$messages = new Messages();

		$reply_id = RouterHelper::getParam('reply_id');

		$reply = $messages->getReply($reply_id);

		$user_id = $this->getUser()->getID();

		if($reply===false || intval($reply['user_id_create'])!=$user_id){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Доступ к ответу ограничен. Возможно, он был удален', 'Ошибка доступа!')
				->execute();
		}

		$message = $messages->getMessageByID($reply['message_id']);

		if($message===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Сообщение недоступно. Возможно, оно еще не создано или уже удалено', 'Ошибка доступа!')
				->execute();
		}

		if(intval($message['is_close'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Беседа закрыта', 'Ошибка доступа!')
				->execute();
		}

		$update = $messages->messageReplySave($reply_id, @$_POST['text']);

		if($update===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка обновления ответа. Обратитесь к администрации', 'Ошибка!')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Ответ успешно отредактирован', 'Поздравляем!', true, [
				'comment' => $this->getTemplater()->render('Resources/Profile/Messages/View/tpl/reply-id.tpl', [
					'reply' => array_replace_recursive($reply, $update),
				])
			])
			->execute();
	}

	public function messageLock(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$params = @$_POST;

		$value = (intval(@$params['value'])==1) ? 1 : 0;

		$link_id = intval(@$params['link_id']);

		$messages = new Messages();

		$message = $messages->getMessageByLink($link_id);

		$_user = $this->getUser();

		$user_id = $_user->getID();

		$user_id_create = intval($message['user_id_create']);

		if(!Permissions::equal('profile_messages_lock', true) &&
			(!Permissions::equal('profile_messages_lock_self', true) || $user_id!=$user_id_create)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для изменения статуса сообщения', 'Недостаточно прав!')
				->execute();
		}

		if($value){
			Logger::base("Закрытие беседы", "Изменение статуса беседы #{$link_id}", __METHOD__, 'profile_message_lock');
		}else{
			Logger::base("Открытие беседы", "Изменение статуса беседы #{$link_id}", __METHOD__, 'profile_message_unlock');
		}

		if(!$messages->setStatus($message['id'], $value)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка изменения статуса сообщения. Обратитесь к администрации', 'Ошибка!')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)->message('Статуса сообщения успешно изменен', 'Поздравляем!', true)->execute();
	}

	public function messageNew(){

		if(!Permissions::equal('profile_messages_send', true)){
			Alerts::set()->message('У вас недостаточно прав для создания сообщений', 'Ошибка доступа!')
				->redirect()->execute();
		}

		$stats = new Stats();

		echo $this->getTemplater()->render('Resources/Profile/Messages/New/tpl/index.tpl', [
			'pagename' => "Создание | Сообщения | Личный кабинет",
			'stats' => $stats->getUserStats(),
			'login' => RouterHelper::getParam('login')
		]);

		exit;
	}

	public function messageNewCreate(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('profile_messages_send', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для создания сообщений', 'Ошибка доступа!')
				->execute();
		}

		$filter = new _Input($_POST);

		$filter->add('to', _Input::TYPE_STRING, 1, 32, true)
			->add('subject', _Input::TYPE_STRING, 1, 255, true)
			->add('text', _Input::TYPE_STRING, 1, 65536, true);

		if(!$filter->isValid()){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Заполните обязательные поля', 'Внимание!')
				->execute();
		}

		$data = $filter->filter();

		$_user = $this->getUser();

		$user = $_user->getUserByLogin($data['to']);

		if($user===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Указанный пользователь не найден', 'Ошибка!')
				->execute();
		}

		$user_id_to = intval($user['id']);

		if($user_id_to==$_user->getID()){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Нельзя отправить сообщение самому себе', 'Ошибка!')
				->execute();
		}

		$messages = new Messages();

		$link_id = $messages->createMessage($data['subject'], $data['text'], $user_id_to);

		if(!$link_id){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка создания сообщения', 'Ошибка!')
				->execute();
		}

		Logger::base("Создение беседы", "Была создна беседа \"{$data['subject']}\" с пользователем \"{$user['login']}\"", __METHOD__, 'profile_message_create');

		Alerts::set()->logic(Alerts::JSON_LOGIC)->message('Сообщение успешно отправлено', 'Поздравляем!', true, [
			'link_id' => $link_id
		])->execute();
	}

	public function activity(){

		$_user = $this->getUser();

		$config = RouterHelper::getAppConfig();

		if(!$_user->isAuth()){
			Alerts::set()->message('Для доступа к выбранному разделу, Вам необходимо авторизоваться', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		if(!Permissions::equal('profile_activity_list', true)){
			Alerts::set()->message('У вас недостаточно прав для просмотра истории активности', 'Ошибка доступа!')
				->redirect()->execute();
		}

		$activity = new Activity();

		$activity->setNames($config['logger']['activity']);

		$page_id = RouterHelper::getParam('page_id');

		$user_id = $_user->getID();

		$pagination = $activity->getPagination($user_id, $page_id)->execute();

		$stats = new Stats();

		echo $this->getTemplater()->render('Resources/Profile/Activity/tpl/index.tpl', [
			'pagename' => 'История активности | Личный кабинет',
			'stats' => $stats->getUserStats(),
			'activity' => $activity->getActivity($user_id, $page_id),
			'activity_num' => $activity->getCount($user_id),
			'pagination' => $pagination,
		]);

		exit;
	}

	public function settings(){

		$config = RouterHelper::getAppConfig();

		if(!$this->getUser()->isAuth() || !Permissions::equal('profile_settings', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$stats = new Stats();

		echo $this->getTemplater()->render('Resources/Profile/Settings/tpl/index.tpl', [
			'pagename' => 'Настройки | Личный кабинет',
			'stats' => $stats->getUserStats(),
		]);

		exit;
	}

	public function settingsSave(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$result = $this->getProfileModel()->saveProfileSettings($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($result['text'], $result['title'], $result['type'])
			->execute();
	}

	public function settingsSecurityComplete(){

		$result = $this->getProfileModel()->settingsSecurityComplete(RouterHelper::getParam('token'));
		$config = RouterHelper::getAppConfig();

		Alerts::set()->message($result['text'], $result['title'])
			->redirect($config['meta']['site_url'])->execute();
	}
}

?>