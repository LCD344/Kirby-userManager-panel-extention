<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 4/10/2016
	 * Time: 2:45 PM
	 */

	namespace lcd344;


	use a;
	use c;
	use cookie;
	use Exception;
	use password;
	use s;
	use str;
	use yaml;

	trait EDBUser {

		private $user;

		public function __construct($username) {
			try {
				$this->user = UserModel::where('username', $username)->firstOrFail();
				$this->user->setCasts(c::get('userManager.database.structures',[]));
			} catch (Exception $e) {
				throw new Exception('The user account could not be found');
			}
		}


		public function username() {
			return $this->user->username;
		}


		public function data() {
			return $this->user->toArray();
		}

		protected function file() {
			throw new Exception("This is a database user. It doesn't have a file");
		}

		public function exists() {
			return $this->user->exists;
		}

		public function login($password) {

			if (!password::match($password, $this->user->password)) return false;

			$data = array();
			if (static::current()) {
				// logout active users first
				static::logout();

				// don't preserve current session data
				// because of privilege level change
			} else {
				// get all the current session data
				$data = s::get();

				// remove anything kirby related from
				// the current session data
				foreach ($data as $key => $value) {
					if (str::startsWith($key, 'kirby_')) {
						unset($data[$key]);
					}
				}
			}

			// start a new session with a new session ID
			s::restart();
			s::regenerateId();

			// copy over the old session stuff
			s::set($data);

			$key = $this->generateKey();
			$secret = $this->generateSecret($key);

			s::set('kirby_auth_secret', $secret);
			s::set('kirby_auth_username', $this->username());

			cookie::set(
				s::$name . '_auth',
				$key,
				s::$cookie['lifetime'],
				s::$cookie['path'],
				s::$cookie['domain'],
				s::$cookie['secure'],
				s::$cookie['httponly']
			);

			return true;

		}

		public function update($data = array()) {

			// sanitize the given data
			$data = $this->sanitize($data, 'update');

			// validate the updated dataset
			$this->validate($data, 'update');

			// don't update the username
			unset($data['username']);

			// create a new hash for the password
			if (!empty($data['password'])) {
				$data['password'] = password::hash($data['password']);
			}

			$fields = $this->user->getAttributes();
			foreach ($data as $key => $val){
				if(! array_key_exists($key,$fields)){
					unset($data[$key]);
				}
			}

			$this->user->update($data);

			// return the updated user project
			return $this;

		}

		public function delete() {

			if ($avatar = $this->avatar()) {
				$avatar->delete();
			}

			if (! $this->user->delete()) {
				throw new Exception('The account could not be deleted');
			} else {
				return true;
			}
		}

		static public function sanitize($data, $mode = 'insert') {

			// all usernames must be lowercase
			$data['username'] = str::slug(a::get($data, 'username'), null, 'a-z0-9@.');

			// convert all keys to lowercase
			$data = array_change_key_case($data, CASE_LOWER);

/*			foreach ($data as $key => $val){
				if (is_array($val)){
					$data[$key] = json_encode($val);
				}
			}*/

			// return the cleaned up data
			return $data;
		}

		static public function create($data = array()) {

			// sanitize the given data for the new user
			$data = static::sanitize($data, 'insert');

			// validate the dataset
			static::validate($data, 'insert');


			// check for an existing username
			if (UserModel::where('username', $data['username'])->first()) {
				throw new Exception('The username is taken');
			}

			// create a new hash for the password
			if (!empty($data['password'])) {
				$data['password'] = password::hash($data['password']);
			}

			static::save($data['username'], $data);

			// return the created user project
			return new static($data['username']);

		}

		static protected function save($file,$data) {

			if (!UserModel::create($data)) {
				throw new Exception('The user account could not be saved');
			} else {
				return true;
			}
		}

		public function toArray() {
			$data = $this->data();
			unset($data['password']);
			return $data;
		}

		public function __toString() {
			return $this->username();
		}
	}