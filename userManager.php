<?php


	require_once __DIR__ . '/Traits/ExtendedUser.php';
	require_once __DIR__ . '/Traits/ExtendedUsers.php';

	if (class_exists('panel')) {
		require_once __DIR__ . '/PanelExtention/View.php';
		require_once __DIR__ . '/PanelExtention/NewUser.php';
		require_once __DIR__ . '/PanelExtention/User.php';
		require_once __DIR__ . '/PanelExtention/Users.php';
		require_once __DIR__ . '/Controllers/UserManagementController.php';
		require_once __DIR__ . '/Routes/router.php';
		$kirby->set('widget', 'userManagementWidget', __DIR__ . '/userManagementWidget');
	} else {
		require_once __DIR__ . '/Classes/User.php';
		require_once __DIR__ . '/Classes/Users.php';
	}


?>