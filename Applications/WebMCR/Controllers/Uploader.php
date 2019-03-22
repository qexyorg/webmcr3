<?php

namespace App\WebMCR\Controllers;

use Framework\Alonity\Controller\Controller;

class Uploader extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}
}

?>