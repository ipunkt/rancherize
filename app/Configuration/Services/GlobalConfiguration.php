<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Exceptions\GlobalConfigurationNotFoundException;
use Rancherize\Configuration\Loader\Loader;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Writer\Writer;

/**
 * Class GlobalConfiguration
 * @package Rancherize\Configuration\Services
 *
 * Loads ${HOME}/.rancherize into global.* of the given configuration
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
	 * @param Configurable $configuration
	 */
	public function load(Configurable $configuration) {

		/**
		 * Global values should be loaded to global.*
		 */
		$prefixDecorator = new PrefixConfigurableDecorator($configuration, 'global.');

		$globalConfigPath = $this->getPath();

		try {
			$this->loader->load($prefixDecorator, $globalConfigPath);
		} catch(FileNotFoundException $e) {
			throw new GlobalConfigurationNotFoundException($e->getPath());
		}
	}

	/**
	 * @param Configurable $configuration
	 */
	public function save(Configurable $configuration) {

		/**
		 * Only values under the `global` key should be written to the global config
		 */
		$prefixDecorator = new PrefixConfigurableDecorator($configuration, 'global');

		$globalConfigPath = $this->getPath();
		$this->writer->write($prefixDecorator, $globalConfigPath);
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return implode('', [
			getenv('HOME'),
			DIRECTORY_SEPARATOR,
			'.rancherize'
		]);
	}

	/**
	 * @param Configurable $configuration
	 */
	public function makeDefault(Configurable $configuration) {
		$configuration->set('global.rancher.default', [
			'url' => 'http://rancher:8080/api/v1',
			'key' => 'key',
			'secret' => 'secret',
		]);
		$configuration->set('global.docker.default', [
			'username' => 'user',
			'password' => 'password',
		]);
	}
}