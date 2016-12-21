<?php
/**
 * Created by PhpStorm.
 * User: lcd34
 * Date: 9/9/2016
 * Time: 7:31 PM
 */

namespace lcd344;


use cookie;
use Exception;
use S;

class User extends \User {

	use ExtendedUser;
	use Mailable;

	static public function current() {

		$cookey = cookie::get(s::$name . '_auth');
		$username = s::get('kirby_auth_username');

		if (empty($cookey)) {
			static::unauthorize();

			return false;
		}

		if (s::get('kirby_auth_secret') !== sha1($username . $cookey)) {
			static::unauthorize();

			return false;
		}

		// find the logged in user by token
		try {
			$user = new static($username);

			return $user;
		} catch (Exception $e) {
			return site()->user();
		}
	}


	public function login($password) {
		if ($this->expiration()) {
			$curdate = strtotime(date("Y-m-d"));
			$expire = strtotime($this->expiration());
			if ($curdate > $expire) {
				return false;
			}
		}

		return parent::login($password);
	}
}