<?php namespace RancherizeTest\Blueprint\Factory;

use Mockery;
use Pimple\Container;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Factory\ContainerBlueprintFactory;
use RancherizeTest\TestCase;

/**
 * Class ContainerFactoryTest
 * @package RancherizeTest\Blueprint\Factory
 */
class ContainerFactoryTest extends TestCase {

	/**
	 * @var Container|Mockery\MockInterface
	 */
	private $container;
/**
	 * @var ContainerBlueprintFactory
	 */
	private $containerFactory;

	const KEY_PREFIX = 'blueprint.';

	public function setUp(  ) {

		$this->container = Mockery::mock(Container::class);

		$this->containerFactory = new ContainerBlueprintFactory($this->container);

	}

	public function tearDown(  ) {

		$this->container = null;
		$this->containerFactory = null;

		parent::tearDown();
	}

	public function blueprintProvider() {
		return [
			[ 'test', 'TestClasspath' ],
			[ 'tst', 'TstClasspath' ],
		];
	}

	/**
	 * @test
	 * @param $name
	 * @param $classpath
	 * @dataProvider blueprintProvider
	 */
	public function blueprintIsAdded( $name, $classpath  ) {

		$this->container->shouldReceive('offsetSet')->with(self::KEY_PREFIX.$name, Mockery::any())->once();

		$this->containerFactory->add($name, $classpath);
	}

	/**
	 * @test
	 * @dataProvider blueprintProvider
	 */
	public function blueprintIsListed( $name, $classpath  ) {

		$this->container->shouldReceive('offsetSet')->with(self::KEY_PREFIX.$name, Mockery::any())->once();

		$this->containerFactory->add($name, $classpath);

		$blueprints = $this->containerFactory->available();

		$this->assertTrue( in_array($name, $blueprints) );
	}

	/**
	 * @param $name
	 * @param $classpath
	 * @test
	 * @dataProvider blueprintProvider
	 */
	public function canRetrieveBlueprint( $name, $classpath ) {

		$expectedBlueprint = Mockery::mock( Blueprint::class );

		$this->container->shouldReceive('offsetSet')->with(self::KEY_PREFIX.$name, Mockery::any())->once();
		$this->container->shouldReceive('offsetGet')->with(self::KEY_PREFIX.$name)->once()->andReturn( $expectedBlueprint );

		$this->containerFactory->add($name, $classpath);
		$returnedBlueprint = $this->containerFactory->get($name);

		$this->assertEquals( $expectedBlueprint, $returnedBlueprint );
	}
}