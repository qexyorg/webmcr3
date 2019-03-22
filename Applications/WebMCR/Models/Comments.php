<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\File\File;
use Framework\Components\Filters\_BBCodes;
use Framework\Components\Pagination\Pagination;
use Framework\Components\Path;
use Framework\Components\Permissions\Permissions;

class Comments {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	/**
	 * @param $comment_id integer
	 *
	 * @return array|boolean
	*/
	public function getComment($comment_id){
		$comment_id = intval($comment_id);

		$cache = Cache::getOnce([__METHOD__, $comment_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`c`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['c' => 'comments'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`c`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`c`.`user_id_update`"])
			->where(["`c`.`id`='?'"], [$comment_id])
			->limit(1);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $comment_id], false);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $comment_id], false);
		}

		return Cache::setOnce([__METHOD__, $comment_id], $ar[0]);
	}

	public function getCommentsCount($where=[], $values=[]){

		$cache = Cache::getOnce([__METHOD__, $where, $values]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('comments')
			->where($where, $values);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $where, $values], 0);
		}

		$ar = $select->getArray();

		$count = (empty($ar)) ? 0 : intval(@$ar[0][0]);

		return Cache::setOnce([__METHOD__, $where, $values], $count);
	}

	public function getLastComments($count=5){

		$result = [];

		$select = Database::select()
			->columns(['`c`.`id`', '`c`.`text_html`', '`c`.`type`', '`c`.`value`'])
			->from(['c' => 'comments'])
			->order(['`c`.`id`' => 'DESC'])
			->group(['`c`.`id`'])
			->limit($count);

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		return $select->getAssoc();
	}

	public function existModRecord($mod, $id){
		$id = intval($id);

		$mod = $this->getMod($mod);

		if(!$mod['values']){ return true; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from($mod['table'])
			->where(["`id`='?'"], [$id]);

		if(!$select->execute()){
			return false;
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return false;
		}

		return (intval(@$ar[0][0])<=0) ? false : true;
	}

	public function add($mod='news', $value=0, $post=[]){

		$value = intval($value);

		if(!isset($post['text']) || empty($post['text'])){
			return [
				'type' => false,
				'title' => 'Ошибка заполнения формы',
				'text' => 'Не заполнено поле текста комментария'
			];
		}

		if(!$this->existModRecord($mod, $value)){
			return [
				'type' => false,
				'title' => 'Ошибка',
				'text' => 'Запись не найдена'
			];
		}

		$comment = $this->insertComment($mod, $value, $post['text']);

		if($comment===false){
			return [
				'type' => false,
				'title' => 'Ошибка базы данных',
				'text' => 'Произошла ошибка добавления комментария [#'.__LINE__.']'
			];
		}

		if(!$this->updateStats()){
			return [
				'type' => true,
				'text' => 'Комментарий успешно добавлен, однако произошла ошибка обновления статистики',
				'title' => 'Внимание!'
			];
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Комментарий успешно добавлен',
			'count' => $this->getCommentsCount(["`type`='?'", "`value`='?'"], [$mod, $value]),
			'comment' => $comment,
		];
	}

	public function save($comment_id, $mod='news', $value=0, $post=[]){

		$comment_id = intval($comment_id);

		$value = intval($value);

		if(!isset($post['text']) || empty($post['text'])){
			return [
				'type' => false,
				'title' => 'Ошибка заполнения формы',
				'text' => 'Не заполнено поле текста комментария'
			];
		}

		if(!$this->existModRecord($mod, $value)){
			return [
				'type' => false,
				'title' => 'Ошибка',
				'text' => 'Запись не найдена'
			];
		}

		$comment = $this->updateComment($comment_id, $post['text']);

		if($comment===false){
			return [
				'type' => false,
				'title' => 'Ошибка базы данных',
				'text' => 'Произошла ошибка редактирования комментария [#'.__LINE__.']'
			];
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Комментарий успешно отредактирован',
			'comment' => $comment,
		];
	}

	public function updateComment($comment_id, $text){

		$text_bb = $text;
		$text_html = _BBCodes::parse($text);

		$user = $this->getUser()->getCurrentUser();

		$date = time();

		$update = Database::update()
			->table('comments')
			->set(['text_bb' => $text_bb, 'text_html' => $text_html, 'user_id_update' => $user['id'], 'date_update' => $date])
			->where(["`id`='?'"], [$comment_id]);

		if(!$update->execute()){
			return false;
		}

		return [
			'text_bb' => $text_bb,
			'text_html' => $text_html,
			'date_update' => $date,
			'user_avatar_update' => $user['avatar'],
			'user_id_update' => $user['id'],
			'user_login_update' => $user['login']
		];
	}

	public function getStatsMods(){
		$config = RouterHelper::getAppConfig();

		if(!isset($config['comment_mods']) || empty($config['comment_mods'])){
			return [];
		}

		$result = [];

		foreach($config['comment_mods'] as $mod => $ar){
			if(!isset($ar['stats']) || !$ar['stats']){
				continue;
			}

			$result[] = $mod;
		}

		return $result;
	}

	public function getCountAllComments($user_id){

		$mods = $this->getStatsMods();

		if(empty($mods)){ return true; }

		$mods = Database::filterIn($mods);

		$mods = implode(',', $mods);

		return $this->getCommentsCount(["`type` IN ($mods)", "`user_id_create`='?'"], [$user_id]);
	}

	public function updateStats($user_id=null){
		if(is_null($user_id)){
			$user_id = $this->getUser()->getID();
		}

		$user_id = intval($user_id);

		$count = $this->getCountAllComments($user_id);

		$update = Database::update()
			->table('user_stats')
			->set(['comments' => $count])
			->where(["`user_id`='?'"], [$user_id]);

		return $update->execute();
	}

	public function insertComment($mod, $value, $text){

		$value = intval($value);

		$text_bb = $text;
		$text_html = _BBCodes::parse($text);

		$user = $this->getUser()->getCurrentUser();

		$date = time();

		$insert = Database::insert()
			->into('comments')
			->columns(['type', 'value', 'text_bb', 'text_html', 'date_create', 'date_update', 'user_id_create', 'user_id_update'])
			->values([$mod, $value, $text_bb, $text_html, $date, $date, $user['id'], $user['id']]);

		if(!$insert->execute()){
			return false;
		}

		return [
			'id' => intval($insert->getLastID()),
			'mod' => $mod,
			'value' => $value,
			'text_bb' => $text_bb,
			'text_html' => $text_html,
			'date_create' => $date,
			'date_update' => $date,
			'user_avatar_create' => $user['avatar'],
			'user_avatar_update' => $user['avatar'],
			'user_id_create' => $user['id'],
			'user_id_update' => $user['id'],
			'user_login_create' => $user['login'],
			'user_login_update' => $user['login'],
			'user' => $user
		];
	}

	public function deleteComment($comment_id){
		$delete = Database::delete()
			->from('comments')
			->where(["`id`='?'"], [$comment_id]);

		return $delete->execute();
	}

	public function remove($mod='news', $value=0, $comment_id){

		$comment_id = intval($comment_id);

		$value = intval($value);

		if(!$this->existModRecord($mod, $value)){
			return [
				'type' => false,
				'title' => 'Ошибка',
				'text' => 'Запись не найдена'
			];
		}

		if(!$this->deleteComment($comment_id)){
			return [
				'type' => false,
				'title' => 'Ошибка базы данных',
				'text' => 'Произошла ошибка удаления комментария [#'.__LINE__.']'
			];
		}

		if(!$this->updateStats()){
			return [
				'type' => true,
				'text' => 'Комментарий успешно удален, однако произошла ошибка обновления статистики',
				'title' => 'Внимание!'
			];
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Комментарий успешно удален',
			'count' => $this->getCommentsCount(["`type`='?'", "`value`='?'"], [$mod, $value]),
		];
	}

	public function getPagination($page_id=1, $type='news', $value=0, $amount=10, $url='page-{PAGE}'){

		$value = intval($value);

		$page_id = intval($page_id);

		$amount = intval($amount);
		if($amount<=0){ $amount = 1; }

		$cache = Cache::getOnce([__METHOD__, $page_id, $type, $value]);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$pagination->setCount($this->getCommentsCount(["`type`='?'", "`value`='?'"], [$type, $value]))
			->setCurrentPage($page_id)
			->setLimit($amount)
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl("{$config['meta']['site_url']}{$url}");

		return Cache::setOnce([__METHOD__, $page_id, $type, $value], $pagination);
	}

	public function getList($page_id=1, $type='news', $value=0, $amount=10, $page='page-{PAGE}', $order_by='id', $order='DESC'){

		$value = intval($value);

		$cache = Cache::getOnce([__METHOD__, $page_id, $type, $value]);
		if(!is_null($cache)){ return $cache; }

		$pagination = $this->getPagination($page_id, $type, $value, $amount, $page);

		$select = Database::select()
			->columns(['`c`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['c' => 'comments'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`c`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`c`.`user_id_update`"])
			->where(["`c`.`type`='?'", "`c`.`value`='?'"], [$type, $value])
			->order(["`c`.`$order_by`" => $order])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $page_id, $type, $value], []);
		}

		return Cache::setOnce([__METHOD__, $page_id, $type, $value], $select->getAssoc());
	}

	public function modComplete($mod){
		if(!is_array($mod) || empty($mod)){
			return false;
		}

		$array = ['type', 'amount', 'prefix', 'order_by', 'order', 'comment_id_tpl', 'table', 'stats', 'values'];

		sort($array);

		$mod = array_keys($mod);

		sort($mod);

		return $mod===$array;
	}

	public function getPermissions($prefix){
		$user = $this->getUser()->getCurrentUser();

		$user['group_id'] = intval($user['group_id']);

		return [
			'add' => Permissions::get("{$prefix}_comments_add", $user['group_id']),
			'view' => Permissions::get("{$prefix}_comments_view", $user['group_id']),
			'edit' => Permissions::get("{$prefix}_comments_edit", $user['group_id']),
			'edit_all' => Permissions::get("{$prefix}_comments_edit_all", $user['group_id']),
			'remove' => Permissions::get("{$prefix}_comments_remove", $user['group_id']),
			'remove_all' => Permissions::get("{$prefix}_comments_remove_all", $user['group_id']),
		];
	}

	public function hasMod($name){
		$config = File::config()
			->name('Config')
			->setPath(Path::app())
			->get();

		return isset($config['comment_mods'][$name]);
	}

	public function getMod($name){
		$config = File::config()
			->name('Config')
			->setPath(Path::app())
			->get();

		if(!$this->hasMod($name)){
			return null;
		}

		return $config['comment_mods'][$name];
	}

	public function setMod($name, $value, $amount=10, $prefix=null, $table=null, $order_by='id', $order='DESC'){
		$config = RouterHelper::getAppConfig();

		if(is_null($prefix)){
			$prefix = $name;
		}

		if(is_null($table)){
			$table = $name;
		}

		$amount = (intval($amount)<=0) ? 1 : intval($amount);

		$config['comment_mods'][$name] = [
			'type' => $name,
			'amount' => $amount,
			'prefix' => $prefix,
			'order_by' => $order_by,
			'order' => $order,
			'comment_id_tpl' => 'Resources/Comments/tpl/comment-id.tpl',
			'table' => $table,
			'stats' => true,
			'values' => (intval($value) > 0),
		];

		$config = File::config()
			->name('Config')
			->setPath(Path::app())
			->setData($config)
			->build();

		return $config->execute();
	}
}

?>