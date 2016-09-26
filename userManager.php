<?php

	require_once __DIR__ . '/Traits/ExtendedUser.php';
	require_once __DIR__ . '/Traits/ExtendedUsers.php';
	require_once __DIR__ . '/Traits/Mailable.php';

	if (class_exists('panel')) {
		require_once __DIR__ . DS . 'Panel/Helpers/helpers.php';
		if (kirby()->plugin('mailer')){
			require_once __DIR__ .  DS . 'Panel/Controllers/UserMailerController.php';
		}
		require_once __DIR__ .  DS . 'Panel/Stubs/View.php';
		require_once __DIR__ .  DS .'Panel/Stubs/NewUser.php';
		require_once __DIR__ .  DS .'Panel/Stubs/UserMailer.php';
		require_once __DIR__ .  DS .'Panel/Models/User.php';
		require_once __DIR__ .  DS .'Panel/Collections/Users.php';
		require_once __DIR__ .  DS .'Panel/Controllers/UserManagementController.php';
		require_once __DIR__ .  DS .'Panel/Router/router.php';
		$kirby->set('widget', 'userManagementWidget', __DIR__ .  DS .'Panel/Widgets//userManagementWidget');
	} else {
		require_once __DIR__ . '/Classes/User.php';
		require_once __DIR__ . '/Classes/Users.php';
	}

?>