<?php

namespace App\WebMCR\Controllers;

use Framework\Alonity\Controller\Controller;

class Comments extends Controller {

	public function remove(){
		return parent::getCurrentView()->remove();
	}

	public function edit(){
		return parent::getCurrentView()->edit();
	}

	public function save(){
		return parent::getCurrentView()->save();
	}

	public function addSubmit(){
		return parent::getCurrentView()->addSubmit();
	}

	public function quote(){
		return parent::getCurrentView()->quote();
	}
}

?>