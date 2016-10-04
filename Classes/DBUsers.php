<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 3/10/2016
	 * Time: 8:07 PM
	 */

	namespace lcd344;


	class Users extends \Users {

		public function __construct() {

			$dbusers = UserModel::all();

			foreach ($dbusers as $dbuser) {

				$user = new User($dbuser->username);
				$this->append($user->username(), $user);
			}
		}

		public function create($data) {
			return User::create($data);
		}
	}