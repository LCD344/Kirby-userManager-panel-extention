<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 20/9/2016
	 * Time: 6:41 PM
	 */

	namespace lcd344\Panel\Models;

	use a;
	use Exception;
	use kirby;
	use lcd344\EDBUser;
	use str;

	class User extends \Kirby\Panel\Models\User {

		use EDBUser {
			delete as parentDelete;
			update as parentUpdate;
		}

		public function uri($action = 'edit') {
			if ($action == "email") {
				return "userMailer/{$this->username()}";
			}

			return 'userManagement/' . $this->username() . '/' . $action;
		}

		public function delete() {

			if (!panel()->user()->isAdmin() and !$this->isCurrent()) {
				throw new Exception(l('users.delete.error.permission'));
			}

			if (\c::get('userManager.folder', null) == null && $this->isLastAdmin()) {
				// check the number of left admins to not delete the last one
				throw new Exception(l('users.delete.error.lastadmin'));
			}

			$this->parentDelete();

			// flush the cache in case if the user data is
			// used somewhere on the site (i.e. for profiles)
			kirby()->cache()->flush();

			kirby()->trigger('panel.user.delete', $this);

		}

		public function update($data = array()) {

			// keep the old state of the user object
			$old = clone $this;

			if (!panel()->user()->isAdmin() and !$this->isCurrent()) {
				throw new Exception(l('users.form.error.update.rights'));
			}

			// users which are not an admin cannot change their role
			if (!panel()->user()->isAdmin()) {
				unset($data['role']);
			}

			if (str::length(a::get($data, 'password')) > 0) {
				if (a::get($data, 'password') !== a::get($data, 'passwordconfirmation')) {
					throw new Exception(l('users.form.error.password.confirm'));
				}
			} else {
				unset($data['password']);
			}

			if ($this->isLastAdmin() and a::get($data, 'role') !== 'admin') {
				// check the number of left admins to not convert the last one
				throw new Exception(l('user.error.lastadmin'));
			}

			$this->parentUpdate($data);

			// flush the cache in case if the user data is
			// used somewhere on the site (i.e. for profiles)
			kirby()->cache()->flush();

			kirby()->trigger('panel.user.update', array($this, $old));

			return $this;

		}

		protected function file() {
			return kirby::instance()->roots()->accounts() . DS . $this->username() . '_DB_TEMP.php';
		}

		public function isLastAdmin() {
			return false;
		}

		public function isCurrent() {
			return false;
		}

		public function topbar($topbar) {
			$topbar->append(purl('userManagement'), "User Manager");
			$topbar->append($this->url(), $this->username());
		}

		static public function current() {
			return null;
		}
	}