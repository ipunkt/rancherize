<?php

// Autoload from inside the rancherize repository
if( file_exists(__DIR__.'/../vendor/autoload.php') ) {
	require __DIR__.'/../vendor/autoload.php';
} else {
	// Autoload from inside the vendor package directory
	// inside vendor directory. app -> rancherize -> ipunkt / vendor
	require __DIR__.'/../../../autoload.php';
}

require __DIR__.'/container.php';

require_once __DIR__ . '/../plugin_path.php';
