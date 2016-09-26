<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 24/9/2016
	 * Time: 4:53 PM
	 */

	namespace lcd344\Panel\Stubs;


	class UserMailer {

		private $user;

		public function __construct($user) {

			$this->user = $user;
		}

		public function topbar($topbar) {
			$topbar->append(purl('userManagement'), "User Manager");
			$topbar->append($this->user->url(), $this->user->username());
			$topbar->append(purl("userMailer/" . $this->user->username()), "Send Email");
		}

		public function form($callback){
			return panel()->form(kirby()->roots->plugins() . DS . "userManager/Panel/Forms/sendMail.php", $this->user, $callback);
		}

		public function url($action){
			return purl("userMailer/" . $this->user->username(). "/" . $action);
		}
	}