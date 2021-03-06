<?php namespace Rancherize\Plugin\Installer;

use Rancherize\Plugin\Composer\ComposerPacketNameParser;
use Rancherize\Plugin\Composer\ComposerPacketPathMaker;
use Rancherize\Plugin\Exceptions\ComposerPacketNotRancherizePluginException;
use Rancherize\Plugin\Exceptions\InstallFailedException;
use Rancherize\Services\ProcessTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class ComposerPluginInstaller
 */
class ComposerPluginInstaller implements PluginInstaller {

	use ProcessTrait;

	/**
	 * @var ComposerPacketNameParser
	 */
	private $nameParser;
	/**
	 * @var ComposerPacketPathMaker
	 */
	private $pathMaker;

	/**
	 * ComposerPluginInstaller constructor.
	 * @param ComposerPacketNameParser $nameParser
	 * @param ComposerPacketPathMaker $pathMaker
	 */
	public function __construct(ComposerPacketNameParser $nameParser, ComposerPacketPathMaker $pathMaker) {
		$this->nameParser = $nameParser;
		$this->pathMaker = $pathMaker;
	}

	/**
	 * @param $name
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	public function install($name, InputInterface $input, OutputInterface $output) {

		$this->setOutput($output);
		$this->requireProcess();

		$command = [ 'composer', 'require', '--dev', $name];
		$process = ProcessBuilder::create( $command )
			->setTimeout(null)
			->getProcess();
		$this->processHelper->run($output, $process, "Installation of packet $name failed");
		if( !$process->isSuccessful() )
			throw new InstallFailedException();
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getClasspath($name) {

		$composerPacket = $this->nameParser->parse($name);
		$path = $this->pathMaker->makePath($composerPacket);
		$composerJson = $path . '/composer.json';

		$composerContent = file_get_contents( $composerJson );
		$composerData = json_decode( $composerContent, true );

		if( !array_key_exists('extra', $composerData) )
			throw new ComposerPacketNotRancherizePluginException($name, 'extra');

		$extra = $composerData['extra'];
		if( !array_key_exists('rancherize-provider', $extra) )
			throw new ComposerPacketNotRancherizePluginException($name, 'extra.rancherize-provider');

		return $extra['rancherize-provider'];
	}
}