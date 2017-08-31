<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 3/10/2016
	 * Time: 2:44 PM
	 */

	namespace lcd344;

	use Illuminate\Database\Eloquent\Model;

	class UserModel extends Model {

		protected $table = 'users';
		protected $guarded = [];
		protected $connection = 'lcd344.userManager';

		public function setCasts($casts) {
			$this->casts = $casts;
		}
	}