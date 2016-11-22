<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Loader\Loader;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Writer\Writer;

/**
 * Class GlobalConfiguration
 * @package Rancherize\Configuration\Services
 */
class GlobalConfiguration {
	/**
	 * @var Loader
	 */
	private $loader;
	/**
	 * @var Writer
	 */
	private $writer;

	/**
	 * GlobalConfiguration constructor.
	 * @param Loader $loader
	 * @param Writer $writer
	 */
	public function __construct(Loader $loader, Writer $writer) {
		$this->loader = $loader;
		$this->writer = $writer;
	}

	/**
	 *
	 */
	public function load() {
		/**
		 * @var Configuration|Configurable $configuration
		 */
		$configuration = container('configuration');

		/**
		 * Global values should be loaded to global.*
		 */
		$prefixDecorator = new PrefixConfigurableDecorator($configuration, 'global.');

		$globalConfigPath = $this->getConfigPath();

		try{
			$this->loader->load($prefixDecorator, $globalConfigPath);
		} catch(FileNotFoundException $e) {
			// No config yet, nothing to do
		}

	}

	/**
	 *
	 */
	public function save() {
		/**
		 * @var Configuration|Configurable $configuration
		 */
		$configuration = container('configuration');

		/**
		 * Only values under the `global` key should be written to the global config
		 */
		$prefixDecorator = new PrefixConfigurableDecorator($configuration, 'global.');

		$globalConfigPath = $this->getConfigPath();
		$this->writer->write($prefixDecorator, $globalConfigPath);
	}

	/**
	 * @return string
	 */
	private function getConfigPath() {
		return implode('', [
			getenv('HOME'),
			DIRECTORY_SEPARATOR,
			'.rancherize'
		]);
	}
}