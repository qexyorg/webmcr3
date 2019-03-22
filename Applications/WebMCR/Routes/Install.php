<?php

return [
	'install_main' => [
		'pattern' => '/install',
		'controller' => 'Install\Main',
	],

	'install_start' => [
		'pattern' => '/install/start',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'start',
	],

	'install_check_connect' => [
		'pattern' => '/install/dbconnect',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'checkConnectDB',
	],

	'install_reinstall' => [
		'pattern' => '/install/reinstall',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'reinstall',
	],

	'install_step_1' => [
		'pattern' => '/install/step_1',
		'controller' => 'Install\Main',
		'action' => 'install_step_1'
	],

	'install_step_1_submit' => [
		'pattern' => '/install/step_1/submit',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'install_step_1_submit'
	],

	'install_step_2' => [
		'pattern' => '/install/step_2',
		'controller' => 'Install\Main',
		'action' => 'install_step_2'
	],

	'install_step_2_submit' => [
		'pattern' => '/install/step_2/submit',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'install_step_2_submit'
	],

	'install_step_3' => [
		'pattern' => '/install/step_3',
		'controller' => 'Install\Main',
		'action' => 'install_step_3'
	],

	'install_step_3_submit' => [
		'pattern' => '/install/step_3/submit',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'install_step_3_submit'
	],

	'install_finish' => [
		'pattern' => '/install/finish',
		'controller' => 'Install\Main',
		'action' => 'install_finish'
	],

	'install_disable' => [
		'pattern' => '/install/disable',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'install_disable'
	],

	'install_remove' => [
		'pattern' => '/install/remove',
		'controller' => 'Install\Main',
		'methods' => 'POST',
		'action' => 'install_remove'
	],
];

?>