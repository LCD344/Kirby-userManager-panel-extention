<?php
	use Illuminate\Database\Capsule\Manager as Capsule;

	$capsule = new Capsule;

	$capsule->addConnection(array(
		'driver' => c::get("userManager.database.driver","mysql"),
		'host' => c::get("userManager.database.host","localhost"),
		'database' => c::get("userManager.database.db"),
		'username' => c::get("userManager.database.username"),
		'password' => c::get("userManager.database.password"),
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => ''
	));

	$capsule->bootEloquent();
?>