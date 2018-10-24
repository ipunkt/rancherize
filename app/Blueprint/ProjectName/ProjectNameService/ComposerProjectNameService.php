<?php namespace Rancherize\Blueprint\ProjectName\ProjectNameService;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\File\FileLoader;
use Rancherize\Plugin\Composer\ComposerPacketNameParser;

/**
 * Class ComposerProjectNameService
 * @package Rancherize\Blueprint\ProjectName\ProjectNameService
 *
 * Read the project name from the composer.json `name` field.
 */
class ComposerProjectNameService implements ProjectNameService {
	/**
	 * @var FileLoader
	 */
	private $fileLoader;

	/**
	 * @var string
	 */
	private $composerPath = 'composer.json';
	/**
	 * @var ComposerPacketNameParser
	 */
	private $nameParser;

	/**
	 * ComposerProjectNameService constructor.
	 * @param FileLoader $fileLoader
	 * @param ComposerPacketNameParser $nameParser
	 */
	public function __construct(FileLoader $fileLoader, ComposerPacketNameParser $nameParser) {
		$this->fileLoader = $fileLoader;
		$this->nameParser = $nameParser;
	}

	/**
	 * @param string $composerPath
	 */
	public function setComposerPath( string $composerPath ) {
		$this->composerPath = $composerPath;
	}

	/**
	 * @param Configuration $configuration
	 * @param string $default
	 * @return string
	 */
	public function getProjectName( Configuration $configuration, $default = null ) {

		if($default === null)
			$default = '';


        try {
            $composerString = $this->fileLoader->get($this->composerPath);
        } catch (FileNotFoundException $e) {
            return 'ProjectName';
        }
            $composerData = json_decode($composerString, true);

		if( !array_key_exists('name', $composerData) )
			return $default;

		$fullName = $composerData['name'];
		$composerPacket = $this->nameParser->parse($fullName);

		return $composerPacket->getName();
	}
}