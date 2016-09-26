<?php
	$controllerName = \lcd344\Panel\Controllers\UserMailerController::class;

	return [
		[
			'pattern' => 'userMailer/(:all)/field/(:any)/(:all)/(:all)',
			'action' => 'fields',
			'controller' => $controllerName,
			'filter' => 'auth',
			'method' => 'GET|POST'
		],
		[
			'pattern' => 'userMailer/(:all)',
			'action' => 'index',
			'controller' => $controllerName,
			'method' => 'GET|POST',
			'filter' => ['auth']
		]
	]

?>