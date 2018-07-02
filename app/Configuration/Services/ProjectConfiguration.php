<?php namespace Rancherize\Configuration\Services;

use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\Configuration\HasSettableVersion;
use Rancherize\Configuration\Loader\Loader;
use Rancherize\Configuration\PrefixConfigurableDecorator;
use Rancherize\Configuration\Writer\Writer;

/**
 * Class ProjectConfiguration
 * @package Rancherize\Configuration\Services
 *
 * Loads and saves the rancherize.json from the current directory into project.* of the configuration
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
	public function __construct( Loader $loader, Writer $writer ) {
		$this->loader = $loader;
		$this->writer = $writer;
	}

	/**
	 * Load the rancherize.json into project.* of the configuration
	 *
	 * @param Configurable $configuration
	 * @return Configurable|Configuration
	 */
	public function load( Configurable $configuration ) {

		$rancherizePath = $this->getConfigPath();

		try {
			/**
			 * Only values under the `project` key should be written to the project config
			 */
			$prefixDecorator = new PrefixConfigurableDecorator( $configuration, 'project.' );

			$this->loader->load( $prefixDecorator, $rancherizePath );

			if ( $configuration instanceof HasSettableVersion )
				$configuration->setVersion( $prefixDecorator->get('version') );
		} catch ( FileNotFoundException $e ) {
			// No config yet, nothing to do
		}

		return $configuration;
	}

	/**
	 * Save the project part of the configuration
	 *
	 * @param Configuration $configuration
	 */
	public function save( Configuration $configuration ) {

		/**
		 * Only values under the `project` key should be written to the project config
		 */
		$prefixDecorator = new PrefixConfigurableDecorator( $configuration, 'project' );

		$rancherizePath = $this->getConfigPath();

		$this->writer->write( $prefixDecorator, $rancherizePath );

	}

	/**
	 * @return string
	 */
	private function getConfigPath() {
		return implode( '', [
			getcwd(),
			DIRECTORY_SEPARATOR,
			'rancherize.json'
		] );
	}
}