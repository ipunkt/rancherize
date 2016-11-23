<?php

if( file_exists(__DIR__.'/../vendor/autoload.php') ) {
	require __DIR__.'/../vendor/autoload.php';
} else {
	// inside vendor directory. app -> rancherize -> vendor
	require __DIR__.'/../../../autoload.php';
}
require __DIR__.'/container.php';

