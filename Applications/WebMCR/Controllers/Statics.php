<?php

namespace App\WebMCR\Controllers;

use Framework\Alonity\Controller\Controller;

class Statics extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}
}

?>