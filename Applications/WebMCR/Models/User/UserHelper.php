<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13.10.2018
 * Time: 20:49
 */

namespace App\WebMCR\Models\User;

class UserHelper {
	private static $logic = 'App\\WebMCR\\Models\\User\\Logic\\DefaultLogic';

	private static $sessionExpire = 31536000;

	private static $sessionName = 'mcr_user';

	final public static function setUserLogic($logic){
		self::$logic = $logic;
	}

	final public static function getUserLogic(){
		return self::$logic;
	}

	final public static function setSessionName($name){
		self::$sessionName = $name;
	}

	final public static function getSessionName(){
		return self::$sessionName;
	}

	final public static function setSessionExpire($time){
		self::$sessionExpire = $time;
	}

	final public static function getSessionExpire(){
		return self::$sessionExpire;
	}

	public static function getIP(){
		if(!empty($_SERVER['HTTP_CF_CONNECTING_IP'])){
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}elseif(!empty($_SERVER['HTTP_X_REAL_IP'])){
			$ip = $_SERVER['HTTP_X_REAL_IP'];
		}elseif(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return mb_substr($ip, 0, 16, "UTF-8");
	}

	public static function isHTTPS(){
		return (isset($_SERVER['HTTPS']) && strtotime($_SERVER['HTTPS'])=='on');
	}
}