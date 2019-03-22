<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use App\WebMCR\Models\User\UserHelper;
use Framework\Components\Database\Database;
use Framework\Components\Filters\_String;
use Framework\Components\Path;

class Logger {

	private static $enable = true;

	private static $maxLines = 100;

	private static $data = [];

	/** @return User */
	private static function getUser(){
		return DI::get('User');
	}

	/**
	 * Change logger status
	 *
	 * @param $enable boolean
	 *
	 * @return void
	*/
	public static function setStatus($enable){
		self::$enable = ($enable);
	}

	public static function getStatus(){
		return self::$enable;
	}

	public static function setLoggerData($data){
		self::$data = array_replace(self::$data, $data);
	}

	public static function getLoggerData(){
		return self::$data;
	}

	public static function setMaxLines($lines){
		self::$maxLines = intval($lines);
	}

	public static function getMaxLines(){
		return self::$maxLines;
	}

	public static function base($title='', $text='', $controller='', $name=null, $user_id=null, $method=null, $url=null){
		if(!self::getStatus()){
			return true;
		}

		$time = time();

		$ip = UserHelper::getIP();

		if(is_null($name)){
			$name = _String::toLatin($name);
		}

		$data = self::getLoggerData();

		if(!empty($data) && !in_array($name, $data)){
			return true;
		}

		if(is_null($method)){
			$method = $_SERVER['REQUEST_METHOD'];
		}

		if(is_null($url)){
			$url = $_SERVER['REQUEST_URI'];
		}

		if(is_null($user_id)){
			$user_id = self::getUser()->getID();
		}

		$select = Database::select()->columns(['COUNT(*)'])->from('logs');

		if(!$select->execute()){
			$size = 0;
		}else{
			$ar = $select->getArray();

			$size = (empty($ar)) ? 0 : intval($ar[0][0]);
		}

		if($size>=self::$maxLines){
			$delete = Database::delete()
				->from('logs')
				->order(['id' => 'ASC'])
				->limit(1);

			if(!$delete->execute()){
				return false;
			}
		}

		$insert = Database::insert()
			->into('logs')
			->columns(['name', 'title', 'text', 'ip', 'method', 'controller', 'url', 'user_id', 'date'])
			->values([$name, $title, $text, $ip, $method, $controller, $url, $user_id, $time]);

		return $insert->execute();
	}

	public static function file($title='', $text='', $controller='', $name=null, $user_id=null, $method=null, $url=null){
		if(!self::getStatus()){
			return true;
		}

		$time = time();

		$ip = UserHelper::getIP();

		if(is_null($name)){
			$name = md5($text);
		}

		$data = self::getLoggerData();

		if(!empty($data) && !in_array($name, $data)){
			return true;
		}

		if(is_null($method)){
			$method = $_SERVER['REQUEST_METHOD'];
		}

		if(is_null($url)){
			$url = $_SERVER['REQUEST_URI'];
		}

		if(is_null($user_id)){
			$user_id = self::getUser()->getID();
		}

		$config = RouterHelper::getAppConfig();

		$filename = Path::to('/tmp/logs/').date("d-m-Y").'_'.md5($config['csrfString']).'.txt';
		$dirname = dirname($filename);

		$file = (file_exists($filename)) ? file($filename) : [];

		if(sizeof($file)>=self::$maxLines){
			unset($file[0]);
		}

		$logs = implode(PHP_EOL, $file).PHP_EOL."$name|$title|$text|$ip|$method|$controller|$url|$user_id|$time";

		if(!file_exists($dirname)){
			@mkdir($dirname, 0777, true);
		}

		$put = file_put_contents($filename, $logs, LOCK_EX);

		return ($put===false) ? false : true;
	}
}

?>