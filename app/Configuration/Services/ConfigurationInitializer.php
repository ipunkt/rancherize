<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configurable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigurationInitializer
 * @package Rancherize\Configuration\Services
 *
 * Helper for
 */
class ConfigurationInitializer {
	/**
	 * @var OutputInterface
	 */
	private $output;

	/**
	 * ConfigurationInitializer constructor.
	 * @param OutputInterface $output
	 */
	public function __construct(OutputInterface $output) {
		$this->output = $output;
	}

	/**
	 * Create $key with value $value if $key is not already present.
	 * If $set is present then the variable will be set there instead of the configuration
	 *
	 * Also outputs information based on the given verbosity level
	 *
	 * @param Configurable $configurable
	 * @param string $key
	 * @param $value
	 * @param Configurable $set
	 */
	public function init(Configurable $configurable, string $key, $value, Configurable $set = null) {
		if($set === null)
			$set = $configurable;

		if( $configurable->has($key) ) {
			$this->output->writeln( "$key already exists, no default value was set for this variable.", OutputInterface::VERBOSITY_VERBOSE);
			return;
		}

		$this->output->writeln( "$key not found. A default value was set for this variable.", OutputInterface::VERBOSITY_NORMAL );

		$set->set($key, $value);
	}

}