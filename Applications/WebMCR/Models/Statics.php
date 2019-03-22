<?php

namespace App\WebMCR\Models;

use Framework\Components\Database\Database;

class Statics {

	private function filterRoute($route){
		$route = preg_replace('/^[\/]+|[\/]+$/i', '', $route);

		return "{$route}/";
	}

	public function getPage($name){

		$name = $this->filterRoute($name);

		$select = Database::select()
			->columns(['`s`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['s' => 'statics'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`s`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`s`.`user_id_update`"])
			->where(["`s`.`route`='?'", "`s`.`status`='?'"], [$name, 1]);

		if(!$select->execute() || $select->getNum()<=0){
			return null;
		}

		$ar = $select->getAssoc();

		if(empty($ar)){ return null; }

		return $ar[0];
	}
}

?>