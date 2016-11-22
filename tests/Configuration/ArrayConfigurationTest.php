<?php namespace RancherizeTest\Configuration;
use Rancherize\Configuration\ArrayConfiguration;
use RancherizeTest\TestCase;

/**
 * Class ArrayConfigurationTest
 * @package RancherizeTest\Configuration
 *
 */
class ArrayConfigurationTest extends TestCase  {

	/**
	 * @test
	 */
	public function detects_nested_values() {

		$configuration = new ArrayConfiguration([
			'a' => [
				'b' => 'c'
			]
		]);

		$hasValue = $configuration->has('a.b');

		$this->assertTrue($hasValue);

	}

	/**
	 * @test
	 */
	public function get_nested_value() {

		$configuration = new ArrayConfiguration([
			'a' => [
				'b' => 'c'
			]
		]);

		$value = $configuration->get('a.b');

		$this->assertEquals('c', $value);

		$aValue = $configuration->get('a');
		$this->assertTrue( is_array($aValue) );
		$this->assertArrayHasKey( 'b', $aValue );

	}

	/**
	 * @test
	 */
	public function can_set_nested_values() {

		$configuration = new ArrayConfiguration();

		$configuration->set('a.b', 'c');

		$value = $configuration->get('a.b');

		$this->assertEquals('c', $value);

		$aValue = $configuration->get('a');
		$this->assertTrue( is_array($aValue) );
		$this->assertArrayHasKey( 'b', $aValue );

	}

	/**
	 * @test
	 */
	public function can_overwrite_values_with_nesting() {
		$configuration = new ArrayConfiguration();

		$configuration->set('a', 'c');
		$this->assertEquals('c', $configuration->get('a') );

		$configuration->set('a.b', 'c');
		$this->assertEquals('c', $configuration->get('a.b') );
	}
}