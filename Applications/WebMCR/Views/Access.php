<?php

use Alonity\Components\Filters\_String;

class AccessViewException extends \Exception {}

class AccessView {

	/** @var UserModel  */
	private $user = null;

	/** @var \Alonity\Alonity() */
	private $alonity = null;

	/** @var MailModel  */
	private $mail = null;

	private $app = [];

	public function __construct($alonity){
		$this->mail = $alonity->mail;

		$this->alonity = $alonity;

		$this->user = $alonity->user;

		$this->app = $this->alonity->getApp($this->alonity->getAppKey());
	}

	public function indexView(){

		$meta = $this->app['meta'];
		$meta['token'] = $this->user->getToken();

		array_walk_recursive($meta, function(&$value){
			$value = _String::toEntities($value);
		});

		$this->alonity->View()->writeView('/Themes/'._THEME_.'/Resources/403/tpl/index.tpl', [
			'app' => $this->app,
			'meta' => $meta,
			'user' => $this->user,
			'userinfo' => $this->user->getCurrentUserHtml(),
			'pagename' => 'Доступ запрещен'
		]);
	}
}

?>