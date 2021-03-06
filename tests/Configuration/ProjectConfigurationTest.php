<?php namespace RancherizeTest\Configuration;
use Mockery;
use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\Loader\JsonLoader;
use Rancherize\Configuration\Loader\Loader;
use Rancherize\Configuration\Services\ProjectConfiguration;
use Rancherize\Configuration\Writer\JsonWriter;
use Rancherize\Configuration\Writer\Writer;
use Rancherize\File\FileLoader;
use Rancherize\File\FileWriter;
use RancherizeTest\TestCase;

/**
 * Class ProjectConfigurationTest
 * @package RancherizeTest\Configuration
 */
class ProjectConfigurationTest extends TestCase  {

	/**
	 * @test
	 */
	public function is_loaded_into_global() {

		$fileLoader = Mockery::mock(FileLoader::class);
		$writer = Mockery::mock(Writer::class);

		$expectedProject = [
			'test1' => 'is-present',
			'test2' => [
				'c' => 'present'
			]
		];

		$fileLoader->shouldReceive('get')->andReturn(
			json_encode($expectedProject)
		);

		$configuration = new ArrayConfiguration();

		$globalConfiguration = new ProjectConfiguration(new JsonLoader($fileLoader), $writer);
		$globalConfiguration->load($configuration);

		$this->assertTrue( $configuration->has('project') );
		$this->assertTrue( $configuration->has('project.test1') );
		$this->assertTrue( $configuration->has('project.test2') );
		$this->assertTrue( $configuration->has('project.test2.c') );

		$this->assertEquals( 'is-present', $configuration->get('project.test1') );
		$this->assertEquals( 'present', $configuration->get('project.test2.c') );

	}

	/**
	 * @test
	 */
	public function saves_only_global() {

		$loader = Mockery::mock(Loader::class);
		$fileWriter = Mockery::mock(FileWriter::class);


		$configuration = new ArrayConfiguration();
		$configuration->set('project.a', 'is-present');
		$configuration->set('b', 'not-present');

		$fileWriter->shouldReceive('put')->with(Mockery::any(), Mockery::on(function($content) {
			$decoded = json_decode($content, true);
			$this->assertArrayHasKey('a', $decoded);
			$this->assertEquals('is-present', $decoded['a']);
			$this->assertArrayNotHasKey('b', $decoded);

			return true;
		}));

		$globalConfiguration = new ProjectConfiguration($loader, new JsonWriter($fileWriter));
		$globalConfiguration->save($configuration);
	}
}