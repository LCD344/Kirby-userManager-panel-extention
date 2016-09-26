<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 24/9/2016
	 * Time: 11:35 AM
	 */

	namespace lcd344;


	use Exception;

	trait Mailable {

		public $mailer;

		public function createMail($service = false, $options = false){
			if(class_exists(Mailer::class)){
				$this->mailer = new Mailer($service,$options);
				$this->mailer->to($this->email());

				return $this->mailer;
			}

			throw new Exception("This function depends on the Kirby Mailer Wrapper. Get it from https://github.com/LCD344/kirby-mailer-wrapper");
		}
	}