<?php namespace RancherizeTest\Configuration;
use Mockery;
use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\Loader\JsonLoader;
use Rancherize\File\FileLoader;
use RancherizeTest\TestCase;

/**
 * Class JsonLoaderTest
 * @package RancherizeTest\Configuration
 */
class JsonLoaderTest extends TestCase {

	/**
	 * @test
	 */
	public function can_load_json() {

		$fileLoader = Mockery::mock(FileLoader::class);
		$fileLoader->shouldReceive('get')->with("mocked")->andReturn(
			'{
				"global": { "test":"present" }
			}'
		);

		$configuration = new ArrayConfiguration();
		$jsonLoader = new JsonLoader($fileLoader);

		$jsonLoader->load($configuration, "mocked");

		$this->assertTrue( $configuration->has('global') );
		$this->assertTrue( is_array($configuration->get('global') ) );
		$this->assertTrue( $configuration->has('global.test') );
		$this->assertEquals( 'present', $configuration->get('global.test') );
	}
}