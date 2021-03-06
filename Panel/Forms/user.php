<?php

use Kirby\Panel\Event;

return function ($user) {

	$mode = $user ? 'edit' : 'add';
	$content = $user ? $user->data() : array();
	$translations = array();
	$roles = array();

	// make sure the password is never shown in the form
	unset($content['password']);

	// add all languages
	foreach (panel()->translations() as $code => $translation) {
		$translations[$code] = $translation->title();
	}

	// add all roles
	foreach (site()->roles() as $role) {
		if (c::get('userManager.roles', false)) {
			if (in_array($role->id(), c::get('userManager.roles')))
				$roles[$role->id()] = $role->name();
		} else {
			$roles[$role->id()] = $role->name();
		}
	}

	// the default set of fields
	if (c::get('userManager.overrideDefaultFields', false)) {

		if (file_exists(kirby()->roots()->blueprints() . DS . 'users' . DS . 'userManager.override.yml')) {
			$overrideBlueprint = yaml::read(kirby()->roots()->blueprints() . DS . 'users' . DS . 'userManager.override.yml');
			if (isset($overrideBlueprint['fields']) && is_array($overrideBlueprint['fields'])) {
				if (!array_key_exists('username', $overrideBlueprint['fields'])) {
					$fields['username'] = [
						'label' => 'users.form.username.label',
						'type' => 'text',
						'icon' => 'user',
						'autofocus' => $mode != 'edit',
						'required' => true,
						'help' => $mode == 'edit' ? 'users.form.username.readonly' : 'users.form.username.help',
						'readonly' => $mode == 'edit',
					];
				}
				if (!array_key_exists('password', $overrideBlueprint['fields'])) {
					$fields['password'] = [
						'label' => $mode == 'edit' ? 'users.form.password.new.label' : 'users.form.password.label',
						'required' => $mode == 'add',
						'type' => 'password',
						'width' => '1/2',
						'suggestion' => true,
					];
				}
				if (!array_key_exists('passwordConfirmation', $overrideBlueprint['fields'])) {
					$fields['passwordConfirmation'] = [
						'label' => $mode == 'edit' ? 'users.form.password.new.confirm.label' : 'users.form.password.confirm.label',
						'required' => $mode == 'add',
						'type' => 'password',
						'width' => '1/2',
					];
				}
				if (!array_key_exists('roles', $overrideBlueprint['fields'])) {
					$fields['role'] = [
						'label' => 'users.form.role.label',
						'type' => 'select',
						'required' => true,
						'width' => '1/2',
						'default' => site()->roles()->findDefault()->id(),
						'options' => $roles,
						'readonly' => (!panel()->user()->isAdmin() or ($user and $user->isLastAdmin()))
					];
				};
				$fields = a::merge($fields,$overrideBlueprint['fields']);
			} else {
				$fields = [
					'noFields' => [
						'label' => "Warning: You don't have fields defined in your userManager.override.yml file.",
						'type' => 'info'
					],
				];
			}
		} else {
			$fields = [
				'username' => [
					'label' => 'users.form.username.label',
					'type' => 'text',
					'icon' => 'user',
					'autofocus' => $mode != 'edit',
					'required' => true,
					'help' => $mode == 'edit' ? 'users.form.username.readonly' : 'users.form.username.help',
					'readonly' => $mode == 'edit',
				],

				'password' => [
					'label' => $mode == 'edit' ? 'users.form.password.new.label' : 'users.form.password.label',
					'required' => $mode == 'add',
					'type' => 'password',
					'width' => '1/2',
					'suggestion' => true,
				],

				'passwordConfirmation' => [
					'label' => $mode == 'edit' ? 'users.form.password.new.confirm.label' : 'users.form.password.confirm.label',
					'required' => $mode == 'add',
					'type' => 'password',
					'width' => '1/2',
				],

				'role' => [
					'label' => 'users.form.role.label',
					'type' => 'select',
					'required' => true,
					'width' => '1/2',
					'default' => site()->roles()->findDefault()->id(),
					'options' => $roles,
					'readonly' => (!panel()->user()->isAdmin() or ($user and $user->isLastAdmin()))
				],
			];
		}
	} else {
		$fields = array(
			'username' => array(
				'label' => 'users.form.username.label',
				'type' => 'text',
				'icon' => 'user',
				'autofocus' => $mode != 'edit',
				'required' => true,
				'help' => $mode == 'edit' ? 'users.form.username.readonly' : 'users.form.username.help',
				'readonly' => $mode == 'edit',
			),

			'firstName' => array(
				'label' => 'users.form.firstname.label',
				'autofocus' => $mode == 'edit',
				'type' => 'text',
				'width' => '1/2',
			),

			'lastName' => array(
				'label' => 'users.form.lastname.label',
				'type' => 'text',
				'width' => '1/2',
			),

			'email' => array(
				'label' => 'users.form.email.label',
				'type' => 'email',
				'required' => true,
				'autocomplete' => false
			),

			'password' => array(
				'label' => $mode == 'edit' ? 'users.form.password.new.label' : 'users.form.password.label',
				'required' => $mode == 'add',
				'type' => 'password',
				'width' => '1/2',
				'suggestion' => true,
			),

			'passwordConfirmation' => array(
				'label' => $mode == 'edit' ? 'users.form.password.new.confirm.label' : 'users.form.password.confirm.label',
				'required' => $mode == 'add',
				'type' => 'password',
				'width' => '1/2',
			),

			'language' => array(
				'label' => 'users.form.language.label',
				'type' => 'select',
				'required' => true,
				'width' => '1/2',
				'default' => kirby()->option('panel.language', 'en'),
				'options' => $translations
			),

			'role' => array(
				'label' => 'users.form.role.label',
				'type' => 'select',
				'required' => true,
				'width' => '1/2',
				'default' => site()->roles()->findDefault()->id(),
				'options' => $roles,
				'readonly' => (!panel()->user()->isAdmin() or ($user and $user->isLastAdmin()))
			),

		);
	}


	if ($user) {

		// add all custom fields
		foreach ($user->blueprint()->fields()->toArray() as $name => $field) {

			if (array_key_exists($name, $fields)) {
				continue;
			}

			$fields[$name] = $field;

		}

	}

	// setup the form with all fields
	$form = new Kirby\Panel\Form($fields, $content);

	// setup the url for the cancel button
	$form->cancel('users');

	if ($mode == 'add') {
		$form->buttons->submit->value = l('add');
	}

	if ($user) {
		$event = $user->event('update:ui');
	} else {
		$event = panel()->user()->event('create:ui');
	}

	if ($event->isDenied()) {
		$form->disable();
		$form->centered = true;
		$form->buttons->submit = '';
		$form->buttons->cancel = '';
	}

	return $form;

};

