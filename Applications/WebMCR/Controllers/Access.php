<?php

class AccessControllerException extends \Exception {}

class AccessController {

	/** @var \Alonity\Alonity() */
	private $alonity = null;

	public function __construct($alonity){
		$this->alonity = $alonity;
	}

	public function indexAction(){
		$this->alonity->getView()->indexView();
	}
}

?>