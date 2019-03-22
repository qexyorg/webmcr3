<?php
/**
 * User interface of WebMCR 3
 *
 * @author Qexy <admin@qexy.org>
 * @copyright Copyright (c) 2018, Qexy
 * @link http://qexy.org
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @version 1.0.0
 */

namespace App\WebMCR\Models\User;


interface UserInterface {

	/**
	 * Возвращает логику работы с пользователем
	 *
	 * @param $classname string|null
	 *
	 * @return object
	 */
	public function getLogic($classname=null);

	/**
	 * Возвращает идентификатор текущего пользователя
	 *
	 * @return integer
	 */
	public function getID();

	/**
	 * Возращает информацию о текущем пользователе
	 *
	 * @return array|boolean
	 */
	public function getCurrentUser();

	/**
	 * Проверяет, авторизован ли пользователь
	 *
	 * @return boolean
	 */
	public function isAuth();

	/**
	 * Возвращает информацию о пользователе по его идентификатору
	 *
	 * @param $user_id integer
	 *
	 * @return array|boolean
	 */
	public function getUserByID($user_id);

	/**
	 * Возвращает информацию о пользователе по его E-Mail адресу
	 *
	 * @param $email string
	 *
	 * @return array|boolean
	 */
	public function getUserByEmail($email);

	/**
	 * Возвращает информацию о пользователе по его логину
	 *
	 * @param $login string
	 *
	 * @return array|boolean
	 */
	public function getUserByLogin($login);

	/**
	 * Возвращает неотфильтрованную информацию о пользователе
	 *
	 * @param $value mixed
	 * @param $type string
	 *
	 * @return array
	 */
	public function getUser($value, $type='id');

	/**
	 * Создает пользователя
	 *
	 * @param $params array
	 *
	 * @throws UserException
	 *
	 * @return integer|boolean
	 */
	public function createUser($params);

	/**
	 * Обновляет пользователя
	 *
	 * @param $params array
	 * @param $value string|integer
	 * @param $type
	 *
	 * @throws UserException
	 *
	 * @return integer|boolean
	 */
	public function updateUser($params, $value=null, $type='id');

	/**
	 * Удаляет пользователя
	 *
	 * @param $value string|integer
	 * @param $type
	 *
	 * @return boolean
	 */
	public function deleteUser($value, $type='id');

	/**
	 * Возвращает UUID пользователя
	 *
	 * @param $string string
	 * @param $offline boolean
	 *
	 * @return string
	 */
	public function uuid($string, $offline=true);

	/**
	 * Сравнивает пароль с его хэш-суммой
	 *
	 * @param $password string
	 * @param $salt string
	 * @param $hash string
	 *
	 * @return boolean
	 */
	public function checkPassword($password, $salt='', $hash);

	/**
	 * Создает авторизацию пользователя
	 *
	 * @param $user_id integer|null
	 * @param $remember boolean
	 *
	 * @return boolean
	 */
	public function setAuth($user_id, $remember=true);

	/**
	 * Сбрасывает авторизацию пользователя
	 *
	 * @param $user_id integer|null
	 *
	 * @return boolean
	 */
	public function setUnauth($user_id=null);

	/**
	 * Возвращает массив балансов пользователя
	 *
	 * @param $user_id integer|null
	 * @param $isCached boolean
	 *
	 * @return array
	 */
	public function getBalance($user_id=null, $isCached=true);
}