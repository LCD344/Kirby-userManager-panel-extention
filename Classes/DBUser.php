<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 3/10/2016
	 * Time: 2:49 PM
	 */

	namespace lcd344;

	class User extends \User {
		use EDBUser;
		use Mailable;
	}