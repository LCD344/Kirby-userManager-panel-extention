<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 20/9/2016
	 * Time: 6:47 PM
	 */

	namespace lcd344\Panel\Collections;


	use Exception;
	use lcd344\ExtendedUsers;
	use lcd344\Panel\Models\User;

	class Users extends \lcd344\Users {

		public function __construct() {

			parent::__construct();

			$this->map(function ($user) {
				return new User($user->username());
			});

		}

		public function create($data) {

			if ($data['password'] !== $data['passwordconfirmation']) {
				throw new Exception(l('users.form.error.password.confirm'));
			}

			unset($data['passwordconfirmation']);

			$user = parent::create($data);
			kirby()->trigger('panel.user.create', $user);

			return new User($user->username());
		}

		public function topbar($topbar) {
			$topbar->append(purl('userManagement'), "User Manager");
		}
	}