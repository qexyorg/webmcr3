<?php
/**
 * Default user logic interface of WebMCR 3
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

interface LogicInterface {

	/**
	 * @param $value mixed
	 * @param $type string
	 *
	 * @return boolean
	 */
	public function userExists($value, $type='id');

	/**
	 * Создает хэш-сумму пароля
	 *
	 * @param $password string
	 * @param $salt string
	 *
	 * @return string
	 */
	public function createPassword($password, $salt='');

	/**
	 * Сравнивает хэш-сумму пароля со входящим паролем
	 *
	 * @param $password string
	 * @param $salt string
	 * @param $hash string
	 *
	 * @return boolean
	 */
	public function checkPassword($password, $salt='', $hash);

	/**
	 * Авторизует текущего пользователя
	 *
	 * @param $user_id integer
	 * @param $remember boolean
	 *
	 * @return boolean
	 */
	public function setAuth($user_id, $remember=true);

	/**
	 * Производит процесс сброса авторизации пользователя
	 *
	 * @param $user_id integer|null
	 *
	 * @return boolean
	 */
	public function setUnauth($user_id=null);

	/**
	 * Возвращает текущий ID пользователя или 0 в случае, если он не авторизован
	 *
	 * @return integer
	 */
	public function getUserID();

	/**
	 * Проверяет, авторизован ли пользователь или нет
	 * Устанавливает свойство $this->current['user_id']
	 *
	 * @return boolean
	 */
	public function isAuth();

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
	 * Возвращает массив балансов пользователя
	 *
	 * @param $user_id integer|null
	 *
	 * @return array
	 */
	public function getBalance($user_id=null);

	/**
	 * Возвращает кол-во пользователей
	 *
	 * @param $where array
	 * @param $values array
	 *
	 * @return integer
	 */
	public function getUsersCount($where=[], $values=[]);
}