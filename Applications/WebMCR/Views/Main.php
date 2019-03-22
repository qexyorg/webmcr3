<?php

namespace App\WebMCR\Views;

use Framework\Alonity\View\View;

class Main extends View {

	public function index(){

		echo $this->getTemplater()->render('Resources/Main/tpl/index.tpl', [
			'pagename' => 'Главная',
		]);
	}
}

?>