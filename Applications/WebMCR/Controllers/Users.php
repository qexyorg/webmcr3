<?php

namespace App\WebMCR\Controllers;

use Framework\Alonity\Controller\Controller;

class Users extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}

	public function view(){
		return parent::getCurrentView()->view();
	}

	public function autocomplete(){
		return parent::getCurrentView()->autocomplete();
	}


}

?>