<?php

namespace App\WebMCR\Controllers\Admin\Users;

use Framework\Alonity\Controller\Controller;

class Groups extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}

	public function addItem(){
		return parent::getCurrentView()->addItem();
	}

	public function addItemSubmit(){
		return parent::getCurrentView()->addItemSubmit();
	}

	public function editItem(){
		return parent::getCurrentView()->editItem();
	}

	public function editItemSubmit(){
		return parent::getCurrentView()->editItemSubmit();
	}

	public function removeItem(){
		return parent::getCurrentView()->removeItem();
	}
}

?>