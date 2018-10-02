<?php namespace Rancherize\Services;

use Rancherize\Blueprint\Blueprint;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;
use Rancherize\Configuration\Services\ConfigurationFallback;
use Rancherize\Events\ValidatingEvent;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ValidateService
 * @package Rancherize\Services
 *
 * Trigger the given blueprint to validate the given configuration
 */
class ValidateService {
	/**
	 * @var EventDispatcher
	 */
	private $eventDispatcher;

	/**
	 * ValidateService constructor.
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct( EventDispatcher $eventDispatcher) {
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * validate the configuration for the given environment
	 *
	 * @param Blueprint $blueprint
	 * @param string $environment
	 * @param Configuration $configuration
	 */
	public function validate(Blueprint $blueprint, Configuration $configuration, string $environment) {

		$projectConfigurable = new PrefixConfigurationDecorator($configuration, "project.default.");
		$environmentConfigurable = new PrefixConfigurationDecorator($configuration, "project.environments.$environment.");
		$fallbackConfiguration = new ConfigurationFallback($environmentConfigurable, $projectConfigurable);

		$event = ValidatingEvent::make()
			->setBlueprint($blueprint)
			->setConfiguration($fallbackConfiguration)
			->setEnvironment($environment)
			;

		$this->eventDispatcher->dispatch($event::NAME, $event);

		$blueprint->validate($configuration, $environment);
	}

	/**
	 * Print a table with one "field", "message" per row for all messages left in the ValidationFailedException
	 *
	 * @param ValidationFailedException $e
	 * @param OutputInterface $output
	 */
	public function print(ValidationFailedException $e, OutputInterface $output) {

		$table = new Table($output);
		$table->setHeaders( ['Field', 'Problem'] );

		$i = 0;
		foreach( $e->getFailures() as $field => $messages ) {
			foreach($messages as $message)
				$table->setRow( $i++, [$field, $message] );

		}

		$table->render();
	}
}