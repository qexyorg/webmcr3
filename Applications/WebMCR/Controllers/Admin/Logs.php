<?php

namespace App\WebMCR\Controllers\Admin;

use Framework\Alonity\Controller\Controller;

class Logs extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}

	public function removeItem(){
		return parent::getCurrentView()->removeItem();
	}
}

?>