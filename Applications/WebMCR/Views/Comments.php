<?php

namespace App\WebMCR\Views;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\Comments as CommentsModel;
use App\WebMCR\Models\Logger;
use App\WebMCR\Models\User\User;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Cache\Cache;
use Framework\Components\Filters\_String;
use Framework\Components\Secure\CSRF;

class Comments extends View {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	/** @return CommentsModel */
	private function getComments(){
		if(DI::has('Comments')){
			return DI::get('Comments');
		}

		return DI::set('Comments', new CommentsModel());
	}

	private function getMod($name){
		$cache = Cache::getOnce([__METHOD__, $name]);
		if(!is_null($cache)){ return $cache; }

		return Cache::setOnce([__METHOD__, $name], $this->getComments()->getMod($name));
	}

	public function addSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$type = RouterHelper::getParam('mod');

		$mod = $this->getMod($type);

		$comments = $this->getComments();

		if(!$comments->modComplete($mod)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Модуль не укомплектован. Обратитесь к администрации', 'Ошибка')
				->execute();
		}

		$perms = $comments->getPermissions($type);

		if(!$perms['add']){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для добавления комментариев', 'Недостаточно прав')
				->execute();
		}

		$add = $comments->add($type, RouterHelper::getParam('value'), $_POST);

		if(!$add['type']){
			Alerts::set()->logic(Alerts::JSON_LOGIC)->message($add['text'], $add['title'])->execute();
		}

		$comment_id = intval($add['comment']['id']);

		$add['comment'] = $this->getTemplater()->render($mod['comment_id_tpl'], [
			'WIDGET_COMMENTS' => ['permissions' => $comments->getPermissions($type)],
			'comment' => $add['comment'],
		]);

		Logger::base("Добавление комментария", "В разделе \"{$type}\" был добавлен комментарий #{$comment_id}", __METHOD__, 'comments_add');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Комментарий успешно добавлен', 'Поздравляем!', true, $add)
			->execute();
	}

	public function remove(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$type = RouterHelper::getParam('mod');

		$mod = $this->getMod($type);

		$comments = $this->getComments();

		if(!$comments->modComplete($mod)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Модуль не укомплектован. Обратитесь к администрации', 'Ошибка')
				->execute();
		}

		$user_id = $this->getUser()->getID();

		$comment_id = intval(RouterHelper::getParam('comment_id'));

		$comment = $comments->getComment($comment_id);

		if($comment===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный комментарий недоступен', 'Ошибка 404')
				->execute();
		}

		$perms = $comments->getPermissions($type);

		if(!$perms['remove_all'] && (!$perms['remove'] || $user_id!=intval($comment['user_id_create']))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для удаления комментариев', 'Недостаточно прав')
				->execute();
		}

		$remove = $comments->remove($type, $comment['value'], $comment_id);

		Logger::base("Удаление комментария", "В разделе \"{$type}\" был удален комментарий #{$comment_id}", __METHOD__, 'comments_remove');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($remove['text'], $remove['title'], $remove['type'], $remove)
			->execute();
	}

	public function edit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$type = RouterHelper::getParam('mod');

		$mod = $this->getMod($type);

		$comments = $this->getComments();

		if(!$comments->modComplete($mod)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Модуль не укомплектован. Обратитесь к администрации', 'Ошибка')
				->execute();
		}

		$user_id = $this->getUser()->getID();

		$comment_id = intval(RouterHelper::getParam('comment_id'));

		$comment = $comments->getComment($comment_id);

		if($comment===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный комментарий недоступен', 'Ошибка 404')
				->execute();
		}

		if(!$comments->existModRecord($type, $comment['value'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Запись не найдена', 'Ошибка 404')
				->execute();
		}

		$perms = $comments->getPermissions($type);

		if(!$perms['edit_all'] && (!$perms['edit'] || $user_id!=intval($comment['user_id_create']))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для редактирования комментария', 'Недостаточно прав')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Комментарий успешно получен', 'Выполнено', true, [
				'comment' => _String::toEntities($comment['text_bb'])
			])
			->execute();
	}

	public function save(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$type = RouterHelper::getParam('mod');

		$mod = $this->getMod($type);

		$comments = $this->getComments();

		if(!$comments->modComplete($mod)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Модуль не укомплектован. Обратитесь к администрации', 'Ошибка')
				->execute();
		}

		$user_id = $this->getUser()->getID();

		$comment_id = intval(RouterHelper::getParam('comment_id'));

		$comment = $comments->getComment($comment_id);

		if($comment===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный комментарий недоступен', 'Ошибка 404')
				->execute();
		}

		$perms = $comments->getPermissions($type);

		if(!$perms['edit_all'] && (!$perms['edit'] || $user_id!=intval($comment['user_id_create']))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для редактирования комментария', 'Недостаточно прав')
				->execute();
		}

		$edit = $comments->save($comment_id, $type, $comment['value'], $_POST);

		if(!$edit['type']){
			Alerts::set()->logic(Alerts::JSON_LOGIC)->message($edit['text'], $edit['title'])->execute();
		}

		$edit['comment'] = $this->getTemplater()->render($mod['comment_id_tpl'], [
			'WIDGET_COMMENTS' => ['permissions' => $comments->getPermissions($type)],
			'comment' => array_merge($comment, $edit['comment']),
		]);

		Logger::base("Изменение комментария", "В разделе \"{$type}\" был изменен комментарий #{$comment_id}", __METHOD__, 'comments_edit');

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Комментарий успешно отредактирован', 'Поздравляем!', true, $edit)
			->execute();
	}

	public function quote(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$type = RouterHelper::getParam('mod');

		$mod = $this->getMod($type);

		$comments = $this->getComments();

		if(!$comments->modComplete($mod)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Модуль не укомплектован. Обратитесь к администрации', 'Ошибка')
				->execute();
		}

		$comment_id = intval(RouterHelper::getParam('comment_id'));

		$comment = $comments->getComment($comment_id);

		if($comment===false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный комментарий недоступен', 'Ошибка 404')
				->execute();
		}

		if(!$comments->existModRecord($type, $comment['value'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Запись не найдена', 'Ошибка 404')
				->execute();
		}

		$perms = $comments->getPermissions($type);

		if(!$perms['view'] || !$perms['add']){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для цитирования', 'Недостаточно прав')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Комментарий успешно получен', 'Выполнено', true, [
				'comment' => _String::toEntities($comment['text_bb'])
			])
			->execute();
	}
}

?>