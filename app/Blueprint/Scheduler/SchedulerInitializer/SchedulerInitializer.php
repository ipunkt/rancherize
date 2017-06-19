<?php namespace Rancherize\Blueprint\Scheduler\SchedulerInitializer;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Services\ConfigurationInitializer;

/**
 * Class SchedulerInitializer
 * @package Rancherize\Blueprint\Scheduler\SchedulerInitializer
 */
class SchedulerInitializer {
	/**
	 * @var ConfigurationInitializer
	 */
	private $initializer;

	/**
	 * SchedulerInitializer constructor.
	 * @param ConfigurationInitializer $initializer
	 */
	public function __construct(ConfigurationInitializer $initializer) {
		$this->initializer = $initializer;
	}

	/**
	 * @param Configurable $environmentSetter
	 * @param Configurable|null $projectSetter
	 */
	public function init( Configurable $environmentSetter, Configurable $projectSetter = null ) {

		if($projectSetter === null)
			$projectSetter = $environmentSetter;

		$schedulerEnvironmentConfigurable = new PrefixConfigurableDecorator($environmentSetter, 'scheduler.');
		$schedulerProjectConfigurable = new PrefixConfigurableDecorator($projectSetter, 'scheduler.');

		$this->initializer->init($schedulerEnvironmentConfigurable, 'enable', false, $schedulerProjectConfigurable);
		$this->initializer->init($schedulerEnvironmentConfigurable, 'tags', ['apps' => 'true']);
	}
}