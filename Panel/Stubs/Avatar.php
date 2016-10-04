<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 3/10/2016
	 * Time: 9:35 PM
	 */

	namespace lcd344\Panel\Stubs;


	class Avatar extends \Kirby\Panel\Models\User\Avatar {

		public function __construct($user) {

			\Avatar::__construct($user);

			if(!$this->exists()) {
				$this->root = $this->user->avatarRoot('{safeExtension}');
				$this->url  = purl('assets/images/avatar.png');
			}

		}
	}