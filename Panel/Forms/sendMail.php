<?php

	use lcd344\Panel\Stubs\UserMailer;

	return function ($user) {

		$content = $user->data();

		if(!$drivers = \c::get('userManager.mailer.drivers',false)){
			$drivers = ['mail' => 'PHP Mail'];
			if (\c::get('mailer.amazon.key',false) && \c::get('mailer.amazon.secret',false) && \c::get('mailer.amazon.host',false)) {
				$drivers['amazon'] = "Amazon";
			}
			if (\c::get('mailer.postmark.key',false)) {
				$drivers['postmark'] = "Postmark";
			}
			if (\c::get('mailer.mailgun.key',false) && \c::get('mailer.mailgun.domain',false)) {
				$drivers['mailgun'] = "Mailgun";
			}
			if (\c::get('mailer.phpmailer.host',false) && \c::get('mailer.phpmailer.username',false) && \c::get('mailer.phpmailer.password',false)) {
				$drivers['phpmailer'] = "PHPMailer";
			}
		$drivers['log'] = 'Log';
		}

		$fields = array(

			'driver' => [
				'label' => 'Driver',
				'type' => 'select',
				'required' => true,
				'options' => $drivers
			],

			'email' => [
				'label' => 'Email',
				'type' => 'text',
				'icon' => 'user',
				'required' => true,
				'readonly' => true,
			],

			'cc' => [
				'label' => 'CC:',
				'type' => 'tags',
				'width' => '1'
			],

			'bcc' => [
				'label' => 'BCC',
				'type' => 'tags',
				'width' => '1'
			],

			'subject' => [
				'label' => 'Subject',
				'type' => 'text',
				'required' => true,
				'autocomplete' => false
			],

			'body' => [
				'label' => 'Body',
				'required' => true,
				'type' => \c::get('userManager.mailer.editor','textarea'),
				'model' => new UserMailer($user),
				'width' => '1'
			]

		);


		// setup the form with all fields
		$form = new Kirby\Panel\Form($fields, [
			'email' => $content['email']
		]);

		// setup the url for the cancel button
		$form->cancel("userManagement/{$content['username']}/edit");
		$form->buttons->submit->value = "Send";

		return $form;

	};
