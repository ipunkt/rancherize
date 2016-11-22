<?php namespace RancherizeTest\Configuration;
use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use RancherizeTest\TestCase;

/**
 * Class PrefixedConfigurationTest
 * @package RancherizeTest\Configuration
 */
class PrefixedConfigurationTest extends TestCase {

	/**
	 * @test
	 */
	public function can_set_prefixed() {
		$configuration = new ArrayConfiguration();
		$prefixedDecorator = new PrefixConfigurableDecorator($configuration, 'prefix.');

		$prefixedDecorator->set('test', 'is-set');

		$this->assertTrue( $configuration->has('prefix.test') );
		$this->assertEquals( 'is-set', $configuration->get('prefix.test') );
	}

	/**
	 * @test
	 */
	public function can_has_prefixed() {
		$configuration = new ArrayConfiguration();
		$configuration->set('prefix.test', 'is-set');

		$prefixedDecorator = new PrefixConfigurableDecorator($configuration, 'prefix.');


		$this->assertTrue( $prefixedDecorator->has('test') );
	}

	/**
	 * @test
	 */
	public function can_get_prefixed() {
		$configuration = new ArrayConfiguration();
		$configuration->set('prefix.test', 'is-set');

		$prefixedDecorator = new PrefixConfigurableDecorator($configuration, 'prefix.');

		$this->assertEquals( 'is-set', $prefixedDecorator->get('test') );
	}
}