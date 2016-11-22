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
	 * @param Configurable $configuration
	 * @return Configurable|Configuration
	 */
	public function load(Configurable $configuration) {

		$rancherizePath = $this->getConfigPath();

		try{
			$this->loader->load($configuration, $rancherizePath);
		} catch(FileNotFoundException $e) {
			// No config yet, nothing to do
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