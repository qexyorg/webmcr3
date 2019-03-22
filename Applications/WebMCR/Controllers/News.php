<?php

namespace App\WebMCR\Controllers;

use Framework\Alonity\Controller\Controller;

class News extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}

	public function view(){
		return parent::getCurrentView()->view();
	}

	public function newsLike(){
		return parent::getCurrentView()->newsLike();
	}
}

?>