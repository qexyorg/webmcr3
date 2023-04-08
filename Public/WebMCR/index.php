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

$autoloadPath = __ROOT__.'/vendor/autoload.php';

if(!is_file($autoloadPath)){

    if(!is_dir(__ROOT__.'/tmp/composer')){
        @mkdir(__ROOT__.'/tmp/composer', 0777, true);
    }

    if(!is_file(__ROOT__.'/tmp/composer.phar')){
        $composer = @file_get_contents('https://getcomposer.org/composer.phar');

        if($composer === false){
            exit('Check internet connection');
        }

        file_put_contents(__ROOT__.'/tmp/composer.phar', $composer);

        $phar = new Phar(__ROOT__.'/tmp/composer.phar');

        $phar->extractTo(__ROOT__.'/tmp/composer');
    }

    require_once(__ROOT__.'/tmp/composer/vendor/autoload.php');

    chdir('../../');

    $input = new Symfony\Component\Console\Input\ArrayInput(array('command' => 'install'));

    $application = new Composer\Console\Application();

    $application->setAutoExit(false);
    $application->run($input);

    if(!is_file($autoloadPath)){
        exit('Something was wrong!');
    }

    header('Refresh:0');

    exit();
}

require_once($autoloadPath);

$app = new Framework\Alonity\Alonity();

$app->run();

//echo microtime(true)-$start;
