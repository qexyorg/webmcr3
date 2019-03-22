<?php

namespace App\WebMCR\Controllers\Admin;

use Framework\Alonity\Controller\Controller;

class Main extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}
}

?>