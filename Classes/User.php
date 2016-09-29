<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 9/9/2016
	 * Time: 7:31 PM
	 */

	namespace lcd344;


	class User extends \User {

		use ExtendedUser;
		use Mailable;

		public function login($password){
			if($this->expiration()){
				$curdate = strtotime(date("Y-m-d"));
				$expire = strtotime($this->expiration());
				if($curdate > $expire){
					return false;
				}
			}

			return parent::login($password);
		}
	}