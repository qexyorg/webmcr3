<?php

namespace App\WebMCR\Controllers;

use Framework\Alonity\Controller\Controller;

class Subscribes extends Controller {

	public function update(){
		return parent::getCurrentView()->update();
	}
}

?>