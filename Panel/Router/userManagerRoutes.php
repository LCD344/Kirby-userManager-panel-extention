<?php

	$controllerName = \lcd344\Panel\Controllers\UserManagementController::class;

	return [
		[
			'pattern' => 'userManagement/(:all)/field/(:any)/(:any)/(:all)',
			'action' => 'fields',
			'controller' => $controllerName,
			'filter' => 'auth',
			'method' => 'GET|POST'
		],
		[
			'pattern' => 'userManagement',
			'action' => 'index',
			'controller' => $controllerName,
			'method' => 'GET',
			'filter' => ['auth', 'isInstalled']
		],
		[
			'pattern' => 'userManagement/add',
			'action' => 'add',
			'controller' => $controllerName,
			'filter' => 'auth',
			'method' => 'POST|GET'
		],
		[
			'pattern' => 'userManagement/(:all)/delete',
			'action' => 'delete',
			'controller' => $controllerName,
			'filter' => 'auth',
			'method' => 'POST|GET'
		],
		[
			'pattern' => 'userManagement/(:all)/edit',
			'action' => 'edit',
			'controller' => $controllerName,
			'filter'  => 'auth',
			'method'  => 'POST|GET'
		]

	]

?>