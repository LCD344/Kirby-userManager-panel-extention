<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 21/9/2016
	 * Time: 5:56 PM
	 */

	namespace lcd344;


	use dir;
	use f;
	use kirby;
	use lcd344\Panel\Models\User as PanelUser;

	trait ExtendedUsers {

		public function __construct() {

			$root = kirby::instance()->roots()->accounts();
			if(\c::get('userManager.folder',null) != null){
				$root = kirby::instance()->roots()->site() . DS . \c::get('userManager.folder');
			}

			foreach (dir::read($root) as $file) {

				// skip invalid account files
				if (f::extension($file) != 'php') continue;

				if(class_exists("panel")){
					$user = new PanelUser(f::name($file));
				} else {
					$user = new User(f::name($file));
				}
				$this->append($user->username(), $user);

			}

		}

		public function create($data) {

			if(class_exists("panel")){
				return PanelUser::create($data);
			} else {
				return User::create($data);
			}
		}
	}