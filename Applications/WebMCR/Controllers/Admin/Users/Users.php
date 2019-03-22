<?php

namespace App\WebMCR\Controllers\Admin\Users;

use Framework\Alonity\Controller\Controller;

class Users extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
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

	public function banItem(){
		return parent::getCurrentView()->banItem();
	}

	public function banipItem(){
		return parent::getCurrentView()->banipItem();
	}

	public function uploadAvatar(){
		return parent::getCurrentView()->uploadAvatar();
	}
}

?>