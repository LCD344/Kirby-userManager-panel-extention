<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 4/10/2016
	 * Time: 2:02 AM
	 */

	namespace lcd344\Panel\Stubs;


	class Blueprint extends \Kirby\Panel\Models\User\Blueprint {

		public function load() {

			// get the user role and load the
			// correspondant blueprint if available
			$this->name = basename(strtolower($this->user->role()));

			// try to find a user blueprint
			$file = kirby()->get('blueprint', 'users/' . $this->name);


			// fall back to the default user blueprint
			if (!$file) $file = kirby()->get('blueprint', 'users/default');

			if ($file) {
				$this->file = $file;
				$this->yaml = \data::read($this->file, 'yaml');

				// remove the broken first line
				unset($this->yaml[0]);
			}
		}
	}