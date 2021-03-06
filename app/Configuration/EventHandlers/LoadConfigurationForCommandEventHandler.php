<?php namespace Rancherize\Configuration\EventHandlers;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Events\ConfigurationLoadedEvent;
use Rancherize\Configuration\Events\EnvironmentConfigurationLoadedEvent;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\PrefixConfigurationDecorator;
use Rancherize\Configuration\Services\ConfigurationFallback;
use Rancherize\Configuration\Services\ConfigWrapper;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
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
	public function __construct( EventDispatcher $eventDispatcher, ConfigWrapper $configWrapper ) {
		$this->eventDispatcher = $eventDispatcher;
		$this->configWrapper = $configWrapper;
	}

	/**
	 * @param ConsoleCommandEvent $event
	 */
	public function prepareCommand( ConsoleCommandEvent $event ) {

		$command = $event->getCommand();

		if ( !$command instanceof LoadsConfiguration )
			return;

		$configuration = $this->loadConfiguration();

		$this->environmentConfiguration( $configuration, $event->getInput() );

		$command->setConfiguration( $configuration );

	}

	/**
	 * @return Configurable
	 *
	 * @TODO: Move into ConsoleVents::COMMAND Event - if $command instanceOf RequiresConfiguration - load config
	 */
	private function loadConfiguration() {

		$config = $this->configWrapper->configuration();

		$this->configWrapper->loadGlobalConfig( $config );
		$this->configWrapper->loadProjectConfig( $config );

		$event = new ConfigurationLoadedEvent();
		$event->setConfiguration( $config );
		$this->eventDispatcher->dispatch( $event::NAME, $event );

		return $config;
	}

	/**
	 * @param Configurable $configuration
	 * @param InputInterface $input
	 */
	private function environmentConfiguration( Configurable $configuration, InputInterface $input ) {
		if ( !$input->hasArgument( 'environment' ) )
			return;

		$environment = $input->getArgument( 'environment' );


		$projectConfigurable = new PrefixConfigurationDecorator( $configuration, "project.default." );
		$environmentConfigurable = new PrefixConfigurationDecorator( $configuration, "project.environments.$environment." );
		$fallbackConfiguration = new ConfigurationFallback( $environmentConfigurable, $projectConfigurable );

		$event = new EnvironmentConfigurationLoadedEvent();
		$event->setConfiguration( $configuration );
		$event->setEnvironmentConfiguration( $fallbackConfiguration );
		$this->eventDispatcher->dispatch( $event::NAME, $event );
	}
}