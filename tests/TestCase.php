<?php namespace RancherizeTest;
use Mockery;


/**
 * Class TestCase
 */
class TestCase extends \PHPUnit_Framework_TestCase {
	protected function tearDown() {
		Mockery::close();
		parent::tearDown();
	}

}