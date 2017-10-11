<?php namespace Rancherize\Configuration\EventHandlers;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Events\ConfigurationLoadedEvent;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Services\ConfigWrapper;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class LoadConfigurationForCommandEventHandler
 * @package Rancherize\Configuration\EventHandlers
 */
class LoadConfigurationForCommandEventHandler {
	/**
	 * @var EventDispatcher
	 */
	private $eventDispatcher;
	/**
	 * @var ConfigWrapper
	 */
	private $configWrapper;

	/**
	 * LoadConfigurationForCommandEventHandler constructor.
	 * @param EventDispatcher $eventDispatcher
	 * @param ConfigWrapper $configWrapper
	 */
	public function __construct( EventDispatcher $eventDispatcher, ConfigWrapper $configWrapper) {
		$this->eventDispatcher = $eventDispatcher;
		$this->configWrapper = $configWrapper;
	}

	/**
	 * @param ConsoleCommandEvent $event
	 */
	public function prepareCommand(ConsoleCommandEvent $event) {

		$command = $event->getCommand();

		if(! $command instanceof LoadsConfiguration )
			return;

		$configuration = $this->loadConfiguration();

		$command->setConfiguration($configuration);

	}

	/**
	 * @return Configurable
	 *
	 * @TODO: Move into ConsoleVents::COMMAND Event - if $command instanceOf RequiresConfiguration - load config
	 */
	private function loadConfiguration() {

		$config = $this->configWrapper->configuration();

		$this->configWrapper->loadGlobalConfig($config);
		$this->configWrapper->loadProjectConfig($config);

		/**
		 * @var EventDispatcher $eventSystem
		 */
		$eventSystem = container('event');
		$event = new ConfigurationLoadedEvent();
		$event->setConfiguration($config);
		$eventSystem->dispatch($event::NAME, $event);

		return $config;
	}
}