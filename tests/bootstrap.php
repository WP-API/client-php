<?php
require '../library/WPAPI.php';
WPAPI::register_autoloader();

$testconfig = array();

if (!file_exists('./config.php')) {
	echo 'Please copy config.php.example to config.php and fill in your details';
	die(1);
}

require './config.php';

function wpapi_tests_autoloader($class) {
	// Check that the class starts with "WPAPI_Test_"
	if (strpos($class, 'WPAPI_Test_') !== 0) {
		return;
	}

	$class = str_replace('WPAPI_Test_', '', $class);
	$file = str_replace('_', '/', $class);
	if (file_exists(dirname(__FILE__) . '/' . $file . '.php')) {
		require_once(dirname(__FILE__) . '/' . $file . '.php');
	}
}
spl_autoload_register('wpapi_tests_autoloader');
