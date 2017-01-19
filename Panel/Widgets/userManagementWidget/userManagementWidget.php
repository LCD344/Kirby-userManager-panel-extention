<?php

return [
	'title' => [
		'text' => c::get('userManager.title','User Management'),
		'link' => 'userManagement',
	],
	'options' => [
		[
			'icon' => 'users',
			'link' => 'userManagement',
			'text' => ''
		]
	],
	'html' => function() {
		return '';
	}
];