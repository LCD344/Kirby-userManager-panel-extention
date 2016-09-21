<?php
	namespace lcd344;


	class View extends \Kirby\Panel\View {

		public function __construct($file, array $data) {
			parent::__construct($file, $data);
			$this->_root = kirby()->roots()->plugins() . DS . "UserManager" . DS . "Views";
		}
	}