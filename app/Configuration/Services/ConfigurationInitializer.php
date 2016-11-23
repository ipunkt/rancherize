<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configurable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigurationInitializer
 * @package Rancherize\Configuration\Services
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
	 * @param Configurable $configurable
	 * @param string $key
	 * @param $value
	 * @param Configurable $set
	 */
	public function init(Configurable $configurable, string $key, $value, Configurable $set = null) {
		if($set === null)
			$set = $configurable;

		if( $configurable->has($key) ) {
			if ( $this->output->isVerbose() )
				$this->output->writeln( "$key already exists, not generated." );
			return;
		}

		if ( !$this->output->isQuiet() )
			$this->output->writeln( "$key not found, setting." );

		$set->set($key, $value);
	}

}