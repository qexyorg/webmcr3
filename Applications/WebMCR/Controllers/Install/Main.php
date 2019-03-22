<?php

namespace App\WebMCR\Controllers\Install;

use Framework\Alonity\Controller\Controller;

class Main extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}

	public function start(){
		return parent::getCurrentView()->start();
	}

	public function install_step_1(){
		return parent::getCurrentView()->install_step_1();
	}

	public function install_step_2(){
		return parent::getCurrentView()->install_step_2();
	}

	public function install_step_3(){
		return parent::getCurrentView()->install_step_3();
	}

	public function install_step_1_submit(){
		return parent::getCurrentView()->install_step_1_submit();
	}

	public function install_step_2_submit(){
		return parent::getCurrentView()->install_step_2_submit();
	}

	public function install_step_3_submit(){
		return parent::getCurrentView()->install_step_3_submit();
	}

	public function install_finish(){
		return parent::getCurrentView()->install_finish();
	}

	public function checkConnectDB(){
		return parent::getCurrentView()->checkConnectDB();
	}

	public function reinstall(){
		return parent::getCurrentView()->reinstall();
	}

	public function install_disable(){
		return parent::getCurrentView()->install_disable();
	}

	public function install_remove(){
		return parent::getCurrentView()->install_remove();
	}
}

?>