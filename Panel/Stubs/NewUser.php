<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 21/9/2016
	 * Time: 1:57 AM
	 */

	namespace lcd344\Panel\Stubs;


	class NewUser {
		public function topbar($topbar) {
			$topbar->append(purl('userManagement'), "User Manager");
			$topbar->append(purl('userManagement/add'), l('users.index.add'));
		}
	}