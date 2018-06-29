<?php namespace Rancherize\Application;

use Exception;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Plugin\Loader\ExtraPluginLoaderDecorator;
use Rancherize\Plugin\Loader\PluginLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Rancherize
 * @package Rancherize\Application
 */
class Rancherize {

	/**
	 * @var Application
	 */
	protected $application;

	public function boot() {
		$c = container();

		/**
		 * @var EventDispatcher $dispatcher
		 */
		$dispatcher = $c['event'];
		$this->application = new Application( 'rancherize' );
		$this->application->setDispatcher( $dispatcher );
		// register application in container
		$c['app'] = function () {
			return $this->application;
		};

		$dispatcher->addListener( \Symfony\Component\Console\ConsoleEvents::EXCEPTION, function ( \Symfony\Component\Console\Event\ConsoleExceptionEvent $event ) {

			$e = $event->getException();
			$output = $event->getOutput();


			if ( $e instanceof ValidationFailedException ) {

				$formatter = $output->getFormatter();

				$headline = ' Validation failed ';
				$output->writeln( [
					'',
					' ' . $formatter->format( sprintf( "<error> %s </error>", str_repeat( ' ', strlen( $headline ) ) ) ) . ' ',
					$formatter->format( " <error> $headline </error>" ),
					' ' . $formatter->format( sprintf( "<error> %s </error>", str_repeat( '=', strlen( $headline ) ) ) ) . ' ',
					' ' . $formatter->format( sprintf( "<error> %s </error>", str_repeat( ' ', strlen( $headline ) ) ) ) . ' ',
					"",
				] );

				/**
				 * @var \Rancherize\Services\ValidateService $validateService
				 */
				$validateService = container( 'validate-service' );
				$validateService->print( $e, $output );
			}

		} );

		$dispatcher->addListener( ConsoleEvents::COMMAND, function ( ConsoleCommandEvent $event ) {

			// get the input instance
			$input = $event->getInput();

			// get the output instance
			$output = $event->getOutput();

			// get the command to be executed
			$command = $event->getCommand();

			// get the application
			$application = $command->getApplication();

			$c = container();
			$c['output'] = function () use ( $output ) {
				return $output;
			};
			$c['input'] = function () use ( $input ) {
				return $input;
			};
			$c['application'] = function () use ( $application ) {
				return $application;
			};
			$c['command'] = function () use ( $command ) {
				return $command;
			};
			$c['process-helper'] = function () use ( $command ) {
				return $command->getHelper( 'process' );
			};
		} );

		$internalPlugins = require_once __DIR__ . '/../lists/plugins.php';
		$pluginLoaderExtra = container( ExtraPluginLoaderDecorator::class );
		foreach ( $internalPlugins as $internalPlugin ) {
			/**
			 * @var \Rancherize\Plugin\Loader\ExtraPluginLoaderDecorator $pluginLoaderExtra
			 */
			$pluginLoaderExtra->registerExtra( $internalPlugin );
		}

		try {

			/**
			 * @var \Rancherize\Plugin\Loader\PluginLoader $pluginLoader
			 */
			$pluginLoader = container( PluginLoader::class );
			$pluginLoader->load( $this->application, container() );

		} catch ( Exception $e ) {

			echo "Warning! Load Plugins failed: " . get_class( $e ) . ' ' . $e->getMessage() . PHP_EOL . PHP_EOL;
			echo "Thrown by " . $e->getFile() . ';' . $e->getLine() . ' ' . $e->getCode() . PHP_EOL;
			echo "Trace: " . $e->getTraceAsString();


		}
	}

	public function run() {

		$returnCode = $this->application->run();

		return $returnCode;

	}

}