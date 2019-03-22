<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\Filters\_BBCodes;
use Framework\Components\Pagination\Pagination;

class Messages {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function exists($message_id){
		$cache = Cache::getOnce([__METHOD__, $message_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('messages')
			->where(["`id`='?'"], [$message_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $message_id], false);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $message_id], false);
		}

		return Cache::setOnce([__METHOD__, $message_id], (intval($ar[0][0])>0));
	}

	public function getCount($user_id){
		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $user_id]);

		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('message_links')
			->where(["`user_id`='?'"], [$user_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $user_id], $result);
		}

		$array = $select->getArray();

		if(empty($array)){
			return Cache::setOnce([__METHOD__, $user_id], $result);
		}

		$result = intval(@$array[0][0]);

		return Cache::setOnce([__METHOD__, $user_id], $result);
	}

	public function getPagination($user_id, $page_id){
		$user_id = intval($user_id);
		$page_id = intval($page_id);

		$cache = Cache::getOnce([__METHOD__, $user_id, $page_id]);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$pagination->setCount($this->getCount($user_id))
			->setCurrentPage($page_id)
			->setLimit($config['pagination']['profile']['messages'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'profile/messages/page-{PAGE}');

		return Cache::setOnce([__METHOD__, $user_id, $page_id], $pagination);
	}

	public function getMessages($user_id, $page_id){
		$user_id = intval($user_id);
		$page_id = intval($page_id);

		$pagination = $this->getPagination($user_id, $page_id);

		$select = Database::select()
			->columns(['`m`.*',
				'`ml`.`id`' => '`link_id`',
				'`ml`.`is_read`' => '`is_read`',
				'COUNT(DISTINCT `mr`.`id`)' => '`reply`',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['ml' => 'message_links'])
			->innerjoin('messages', 'm', ["`m`.`id`=`ml`.`message_id`"])
			->leftjoin('users', 'uc', ["`uc`.`id`=`m`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`m`.`user_id_update`"])
			->leftjoin('message_reply', 'mr', ["`mr`.`message_id`=`m`.`id`"])
			->where(["`ml`.`user_id`='?'"], [$user_id])
			->order(['`m`.`id`' => 'DESC'])
			->group(['`m`.`id`'])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		return $select->getAssoc();
	}

	public function getMessageByLink($link_id, $user_id=null){
		$link_id = intval($link_id);

		if(is_null($user_id)){
			$user_id = $this->getUser()->getID();
		}

		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $link_id, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`m`.*',
				'`ml`.`id`' => '`link_id`',
				'`ml`.`is_read`' => '`is_read`',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['ml' => 'message_links'])
			->innerjoin('messages', 'm', ["`m`.`id`=`ml`.`message_id`"])
			->leftjoin('users', 'uc', ["`uc`.`id`=`m`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`m`.`user_id_update`"])
			->where(["`ml`.`id`='?'", "`ml`.`user_id`='?'"], [$link_id, $user_id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $link_id, $user_id], false);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $link_id, $user_id], false);
		}

		return Cache::setOnce([__METHOD__, $link_id, $user_id], $ar[0]);
	}

	public function getMessageByID($message_id){
		$message_id = intval($message_id);

		$cache = Cache::getOnce([__METHOD__, $message_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`m`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['m' => 'messages'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`m`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`m`.`user_id_update`"])
			->where(["`m`.`id`='?'"], [$message_id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $message_id], false);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $message_id], false);
		}

		return Cache::setOnce([__METHOD__, $message_id], $ar[0]);
	}

	public function getReplyList($message_id){
		$message_id = intval($message_id);

		$pagination = $this->getPaginationReply($message_id);

		$select = Database::select()
			->columns(['`mr`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['mr' => 'message_reply'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`mr`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`mr`.`user_id_update`"])
			->where(["`mr`.`message_id`='?'"], [$message_id])
			->order(['`mr`.`id`' => 'DESC'])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		if(!$select->execute() || $select->getNum()<=0){
			return [];
		}

		return $select->getAssoc();
	}

	public function getCountReply($message_id){
		$message_id = intval($message_id);

		$cache = Cache::getOnce([__METHOD__, $message_id]);

		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('message_reply')
			->where(["`message_id`='?'"], [$message_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $message_id], $result);
		}

		$array = $select->getArray();

		if(empty($array)){
			return Cache::setOnce([__METHOD__, $message_id], $result);
		}

		$result = intval(@$array[0][0]);

		return Cache::setOnce([__METHOD__, $message_id], $result);
	}

	public function getPaginationReply($message_id, $page_id=null){
		$message_id = intval($message_id);
		if(is_null($page_id)){ $page_id = intval(RouterHelper::getParam('page_id')); }

		$page_id = ($page_id<=0) ? 1 : intval($page_id);

		$cache = Cache::getOnce([__METHOD__, $message_id, $page_id]);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$pagination->setCount($this->getCountReply($message_id))
			->setCurrentPage($page_id)
			->setLimit($config['pagination']['profile']['reply'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'profile/messages/'.$message_id.'/page-{PAGE}');

		return Cache::setOnce([__METHOD__, $message_id, $page_id], $pagination);
	}

	public function remove($link_id, $user_id){
		$link_id = intval($link_id);
		$user_id = intval($user_id);

		$delete = Database::delete()
			->from('message_links')
			->where(["`id`='?'", "`user_id`='?'"], [$link_id, $user_id]);

		if(!$delete->execute()){
			return false;
		}

		if(!$this->getUser()->updateStats(['messages' => $this->getUserMessagesCount($user_id)], $user_id)){
			return false;
		}

		return true;
	}

	public function replyAdd($message_id, $text){
		$message_id = intval($message_id);

		$text_bb = $text;
		$text_html = _BBCodes::parse($text);

		$user = $this->getUser()->getCurrentUser();

		$user_id = intval($user['id']);

		$time = time();

		$insert = Database::insert()
			->into('message_reply')
			->columns(['message_id', 'text_bb', 'text_html', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$message_id, $text_bb, $text_html, $user_id, $user_id, $time, $time]);

		if(!$insert->execute()){
			return false;
		}

		return [
			'id' => intval($insert->getLastID()),
			'message_id' => $message_id,
			'text_bb' => $text_bb,
			'text_html' => $text_html,
			'user_id_create' => $user_id,
			'user_id_update' => $user_id,
			'date_create' => $time,
			'date_update' => $time,
			'user_login_create' => $user['login'],
			'user_login_update' => $user['login'],
			'user_avatar_create' => $user['avatar'],
			'user_avatar_update' => $user['avatar']
		];
	}

	public function insertReply($message_id, $text){
		$message_id = intval($message_id);

		$text_bb = $text;
		$text_html = _BBCodes::parse($text);

		$_user = $this->getUser();

		$user = $_user->getCurrentUser();

		$user_id = $_user->getID();

		$time = time();

		$insert = Database::insert()
			->into('message_reply')
			->columns(['message_id', 'text_bb', 'text_html', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$message_id, $text_bb, $text_html, $user_id, $user_id, $time, $time]);

		if(!$insert->execute()){
			return false;
		}

		return [
			'id' => intval($insert->getLastID()),
			'message_id' => $message_id,
			'text_bb' => $text_bb,
			'text_html' => $text_html,
			'user_id_create' => $user_id,
			'user_id_update' => $user_id,
			'date_create' => $time,
			'date_update' => $time,
			'user_login_create' => $user['login'],
			'user_login_update' => $user['login'],
			'user_avatar_create' => $user['avatar'],
			'user_avatar_update' => $user['avatar'],
			'type' => true
		];
	}

	public function getReply($reply_id){
		$reply_id = intval($reply_id);

		$cache = Cache::getOnce([__METHOD__, $reply_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`mr`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['mr' => 'message_reply'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`mr`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`mr`.`user_id_update`"])
			->where(["`mr`.`id`='?'"], [$reply_id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $reply_id], false);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $reply_id], false);
		}

		return Cache::setOnce([__METHOD__, $reply_id], $ar[0]);
	}

	public function isMessageAccess($link_id, $user_id=null){
		$user_id = (is_null($user_id)) ? $this->getUser()->getID() : intval($user_id);

		$link_id = intval($link_id);

		$cache = Cache::getOnce([__METHOD__, $link_id, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from(['ml' => 'message_links'])
			->innerjoin('messages', 'm', ["`m`.`id`=`ml`.`message_id`", "`m`.`user_id`='?'", "`m`.`is_close`='?'"], [$user_id, 0])
			->where(["`ml`.`id`='?'"], [$link_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $link_id, $user_id], false);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $link_id, $user_id], false);
		}

		return Cache::setOnce([__METHOD__, $link_id, $user_id], true);
	}

	public function isReplyAccess($reply_id, $user_id=null){
		$reply_id = intval($reply_id);
		$user_id = (is_null($user_id)) ? $this->getUser()->getID() : intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $reply_id, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$reply = $this->getReply($reply_id);

		if($reply===false){
			return Cache::setOnce([__METHOD__, $reply_id, $user_id], false);
		}

		if(intval($reply['user_id_create'])!=$user_id){
			return Cache::setOnce([__METHOD__, $reply_id, $user_id], false);
		}

		return Cache::setOnce([__METHOD__, $reply_id, $user_id], true);
	}

	public function messageReplyRemove($reply_id){
		$reply_id = intval($reply_id);

		$delete = Database::delete()
			->from('message_reply')
			->where(["`id`='?'"], [$reply_id]);

		return $delete->execute();
	}

	public function messageReplySave($reply_id, $text=''){
		$reply_id = intval($reply_id);

		$user = $this->getUser()->getCurrentUser();

		$text_bb = $text;
		$text_html = _BBCodes::parse($text);

		$time = time();

		$update = Database::update()
			->table('message_reply')
			->set(['`text_bb`' => $text_bb, '`text_html`' => $text_html, '`user_id_update`' => $user['id'], '`date_update`' => $time])
			->where(["`id`='?'"], [$reply_id]);

		if(!$update->execute()){
			return false;
		}

		return [
			'user_login_update' => $user['login'],
			'user_avatar_update' => $user['avatar'],
			'text_bb' => $text_bb,
			'text_html' => $text_html,
			'date_update' => $time
		];
	}

	public function setRead($value, $link_id, $user_id=null){
		$value = ($value) ? 1 : 0;

		if(is_null($user_id)){
			$user_id = $this->getUser()->getID();
		}

		$link_id = intval($link_id);

		$update = Database::update()
			->table('message_links')
			->set(['`is_read`' => $value])
			->where(["`id`='?'", "`user_id`='?'"], [$link_id, $user_id]);

		return $update->execute();
	}

	public function setStatus($message_id, $value=0){
		$message_id = intval($message_id);

		$value = (intval($value)==1) ? 1 : 0;

		$update = Database::update()
			->table('messages')
			->set(['`is_close`' => $value])
			->where(["`id`='?'"], [$message_id]);

		return $update->execute();
	}

	public function createMessage($subject, $text, $user_id){

		$text_bb = $text;
		$text_html = _BBCodes::parse($text);

		$time = time();

		$_user = $this->getUser();

		$user_id_create = $_user->getID();

		$insert = Database::insert()
			->into('messages')
			->columns(['user_id', 'subject', 'text_bb', 'text_html', 'is_close', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$user_id, $subject, $text_bb, $text_html, 0, $user_id_create, $user_id_create, $time, $time]);

		if(!$insert->execute()){
			return false;
		}

		$message_id = intval($insert->getLastID());

		$insert = Database::insert()
			->into('message_links')
			->columns(['user_id', 'message_id', 'is_read', 'perm_close', 'perm_delete', 'perm_user_add', 'perm_user_remove', 'perm_moder_add', 'perm_moder_remove', 'user_id_create', 'user_id_update'])
			->values([$user_id, $message_id, 0, 0, 0, 0, 0, 0, 0, $user_id_create, $user_id_create]);

		if(!$insert->execute()){
			return false;
		}

		$insert = Database::insert()
			->into('message_links')
			->columns(['user_id', 'message_id', 'is_read', 'perm_close', 'perm_delete', 'perm_user_add', 'perm_user_remove', 'perm_moder_add', 'perm_moder_remove', 'user_id_create', 'user_id_update'])
			->values([$user_id_create, $message_id, 1, 1, 1, 1, 1, 1, 1, $user_id_create, $user_id_create]);

		if(!$insert->execute()){
			return false;
		}

		$id = intval($insert->getLastID());

		if(!$_user->updateStats(['messages' => $this->getUserMessagesCount($user_id_create)], $user_id_create)){
			return false;
		}

		return $id;
	}

	public function getUserMessagesCount($user_id=null){
		if(is_null($user_id)){
			$user_id = $this->getUser()->getID();
		}

		$user_id = intval($user_id);

		$cache = Cache::getOnce([__METHOD__, $user_id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('message_links')
			->where(["`user_id`='?'"], [$user_id]);

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $user_id], 0);
		}

		$ar = $select->getArray();

		if(empty($ar)){
			return Cache::setOnce([__METHOD__, $user_id], 0);
		}

		return Cache::setOnce([__METHOD__, $user_id], intval(@intval($ar[0][0])));
	}
}

?>