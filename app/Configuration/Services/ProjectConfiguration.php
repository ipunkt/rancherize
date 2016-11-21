<?php namespace Rancherize\Configuration\Services;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\Loader\Loader;
use Rancherize\Configuration\Writer\Writer;

/**
 * Class ProjectConfiguration
 * @package Rancherize\Configuration\Services
 */
class ProjectConfiguration {

	/**
	 * @var string
	 */
	protected $configPath;
	/**
	 * @var Loader
	 */
	private $loader;
	/**
	 * @var Writer
	 */
	private $writer;

	/**
	 * ProjectConfiguration constructor.
	 * @param Loader $loader
	 * @param Writer $writer
	 */
	public function __construct(Loader $loader, Writer $writer) {
		$this->loader = $loader;
		$this->writer = $writer;
	}

	/**
	 * @return Configuration|Configurable
	 */
	public function load() {
		/**
		 * @var Configuration|Configurable $configuration
		 */
		$configuration = container('configuration');

		/**
		 * @var Loader $loader
		 */
		$loader = container('loader');

		$rancherizePath = $this->getConfigPath();

		try{
			$loader->load($configuration, $rancherizePath);
		} catch(FileNotFoundException $e) {
			// Fine, do nothing
		}

		return $configuration;
	}

	/**
	 * @param Configuration $configuration
	 */
	public function save(Configuration $configuration) {

		$rancherizePath = $this->getConfigPath();

		$this->writer->write($configuration, $rancherizePath);

	}

	/**
	 * @return string
	 */
	private function getConfigPath() {
		return implode('', [
			getenv('PWD'),
			DIRECTORY_SEPARATOR,
			'rancherize.json'
		]);
	}
}