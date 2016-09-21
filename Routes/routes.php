<?php
	return [
		[
			'pattern' => 'userManagement',
			'action' => 'index',
			'controller' => 'lcd344\UserManagementController',
			'method' => 'GET',
			'filter' => ['auth', 'isInstalled']
		],
		[
			'pattern' => 'userManagement/add',
			'action' => 'add',
			'controller' => 'lcd344\UserManagementController',
			'filter' => 'auth',
			'method' => 'POST|GET'
		],
		[
			'pattern' => 'userManagement/(:all)/delete',
			'action' => 'delete',
			'controller' => 'lcd344\UserManagementController',
			'filter' => 'auth',
			'method' => 'POST|GET'
		],
		[
			'pattern' => 'userManagement/(:all)/edit',
			'action' => 'edit',
			'controller' => 'lcd344\UserManagementController',
			'filter'  => 'auth',
			'method'  => 'POST|GET'
		]

	]

?>