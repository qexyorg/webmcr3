<?php

namespace App\WebMCR\Controllers\Admin;

use Framework\Alonity\Controller\Controller;

class Settings extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}

	public function save(){
		return parent::getCurrentView()->save();
	}
}

?>