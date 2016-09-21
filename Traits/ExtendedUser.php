<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 21/9/2016
	 * Time: 11:29 AM
	 */

	namespace lcd344;


	trait ExtendedUser {
		public function __construct($username) {

			$this->username = basename($username);

			// check if the account file exists
			if(!file_exists($this->file())) {
				throw new \Exception('The user account could not be found');
			}

		}

		static public function create($data = array()) {

			// sanitize the given data for the new user
			$data = static::sanitize($data, 'insert');

			// validate the dataset
			static::validate($data, 'insert');

			// create the file root
			$filePath = \kirby::instance()->roots()->accounts() . DS . \c::get('userManager.folder');
			if(\c::get('userManager.folder',null) != null){
				$filePath = \kirby::instance()->roots()->site() . DS . \c::get('userManager.folder');
			}

			$file = $filePath . DS . $data['username'] . '.php';

			// check for an existing username
			if (file_exists($file)) {
				throw new \Exception('The username is taken');
			}

			// create a new hash for the password
			if (!empty($data['password'])) {
				$data['password'] = \password::hash($data['password']);
			}

			static::save($file, $data);

			// return the created user project
			return new static($data['username']);

		}

		protected function file() {
			$filePath = \kirby::instance()->roots()->accounts() . DS . \c::get('userManager.folder');
			if(\c::get('userManager.folder',null) != null){
				$filePath = \kirby::instance()->roots()->site() . DS . \c::get('userManager.folder');
			}

			return $filePath . DS . $this->username() . '.php';
		}

		static public function sanitize($data, $mode = 'insert') {

			// all usernames must be lowercase
			$data['username'] = \str::slug(\a::get($data, 'username'),null,'a-z0-9@.');

			// convert all keys to lowercase
			$data = array_change_key_case($data, CASE_LOWER);

			// return the cleaned up data
			return $data;

		}

	}