<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 20/9/2016
	 * Time: 6:47 PM
	 */

	namespace lcd344\Panel\Collections;


	use Exception;
	use c;
	use lcd344\ExtendedUsers;
	use lcd344\Panel\Models\User;

	class Users extends \Kirby\Panel\Collections\Users {

		use ExtendedUsers;

		public function create($data) {

			if(! c::get('userManager.overrideDefaultFields', false)){
				if($data['password'] !== $data['passwordconfirmation']) {
					throw new Exception(l('users.form.error.password.confirm'));
				}
			}

			unset($data['passwordconfirmation']);

			$user = User::create($data);
			kirby()->trigger('panel.user.create', $user);
			return new User($user->username());
		}

		public function topbar($topbar) {
			$topbar->append(purl('userManagement'), "User Manager");
		}
	}