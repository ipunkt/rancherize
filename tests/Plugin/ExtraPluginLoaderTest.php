<?php namespace RancherizeTest\Plugin;

use Mockery;
use Pimple\Container;
use Rancherize\Configuration\Configuration;
use Rancherize\Plugin\Loader\ExtraPluginLoaderDecorator;
use Rancherize\Plugin\Loader\IdentifierNotFoundException;
use Rancherize\Plugin\Loader\Loader;
use Rancherize\Plugin\Loader\NewLoader;
use Rancherize\Plugin\Loader\PluginLoader;
use Rancherize\Plugin\Provider;
use RancherizeTest\TestCase;
use Symfony\Component\Console\Application;

/**
 * Class ExtraPluginLoaderTest
 * @package Plugin
 */
class ExtraPluginLoaderTest extends TestCase {

	/**
	 * @test
	 */
	public function calls_parent_load() {
		$extraPluginLoader = new ExtraPluginLoaderDecorator( new NewLoader() );

		$pluginLoader = Mockery::mock(PluginLoader::class);
		$pluginLoader->shouldReceive('load')->once();

		$extraPluginLoader->setPluginLoader($pluginLoader);

		$application = Mockery::mock(Application::class);
		$configuration = Mockery::mock(Configuration::class);
		$container = Mockery::mock(Container::class);

		$extraPluginLoader->load($configuration, $application, $container);
	}

	/**
	 * @test
	 * @dataProvider pluginNameData
	 */
	public function calls_registered_extras( $pluginNames ) {

		$loader = Mockery::mock( Loader::class );
		$this->assertNotEmpty($pluginNames);
		foreach($pluginNames as $pluginName) {
			var_dump($pluginName);
			$plugin = Mockery::mock( Provider::class );
			$plugin->shouldReceive('setApplication')->once();
			$plugin->shouldReceive('setContainer')->once();
			$plugin->shouldReceive('register')->once();
			$plugin->shouldReceive('boot')->once();

			$loader->shouldReceive('load')->with( $pluginName )->once()->andReturn( $plugin );
		}

		$extraPluginLoader = new ExtraPluginLoaderDecorator($loader);

		$pluginLoader = Mockery::mock(PluginLoader::class);
		$pluginLoader->shouldReceive('load')->once();

		$extraPluginLoader->setPluginLoader($pluginLoader);

		$application = Mockery::mock(Application::class);
		$configuration = Mockery::mock(Configuration::class);
		$container = Mockery::mock(Container::class);
		foreach($pluginNames as $pluginName)
			$extraPluginLoader->registerExtra($pluginName);

		$extraPluginLoader->load($configuration, $application, $container);

	}

	public function pluginNameData(  ) {
		return [
			[ ['testPlugin'] ]
		];
	}

	/**
	 * @test
	 */
	public function new_loader_errors_on_not_exist() {
		$loader = new NewLoader();

		$this->expectException(IdentifierNotFoundException::class);
		$loader->load('does-not-exist');
	}
}