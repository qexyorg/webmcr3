<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

//$start = microtime(true);

ini_set('session.cookie_lifetime', 3600*24*365);

define('__ROOT__', dirname(dirname(__DIR__)));
define('__DS__', DIRECTORY_SEPARATOR);

ini_set("upload_max_filesize", "8M");
ini_set("post_max_size", "8M");
ini_set("upload_tmp_dir", __ROOT__."/tmp/");

if(function_exists('date_default_timezone_set')){
	date_default_timezone_set('Europe/Moscow');
}

header('Content-Type: text/html; charset=UTF-8');

if(!file_exists(__ROOT__.'/tmp')){
	@mkdir(__ROOT__.'/tmp', 0755, true);
}

if(!file_exists(__ROOT__.'/tmp/cache')){
	@mkdir(__ROOT__.'/tmp/cache', 0755, true);
}

if(!isset($_SESSION)){ session_start(); }

require_once(__ROOT__.'/vendor/autoload.php');

$app = new Framework\Alonity\Alonity();

$app->run();

//echo microtime(true)-$start;

?>