<?php

use lcd344\UserManager\Converter;
use lcd344\UserModel;
use lcd344\Users;

define('DS', DIRECTORY_SEPARATOR);

if(isset(getopt('',['bootstrap:'])['bootstrap'])){
	require(getopt('',['bootstrap:'])['bootstrap'] . DS . 'bootstrap.php');
} else {
	require('kirby/bootstrap.php');
}

if(isset(getopt('',['server:'])['server'])) {
	$_SERVER['SERVER_NAME'] = getopt('',['server:'])['server'];
}

$kirby = kirby();
$site  = site();

$kirby->configure();
$kirby->extensions();
$kirby->models();
$kirby->plugins();

require_once ('Converter.php');

if(c::get('userManager.database',false)){
	echo "Converting from files to db" . PHP_EOL;
	Converter::toDatabase(kirby::instance()->roots()->site() . DS . c::get('userManager.folder'));
	echo "Conversion ended";
} else {
	echo "Converting from db to files" . PHP_EOL;
	Converter::toFiles();
	echo "Conversion ended";
}