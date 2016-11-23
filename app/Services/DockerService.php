<?php namespace Rancherize\Services;
use Rancherize\Docker\Exceptions\BuildFailedException;
use Rancherize\Docker\Exceptions\LoginFailedException;
use Rancherize\Docker\Exceptions\PushFailedException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class DockerService
 * @package Rancherize\Services
 */
class DockerService {

	use ProcessTrait;

	/**
	 * @param string $imageName
	 * @param string $dockerfile
	 */
	public function build(string $imageName, $dockerfile = null) {

		if( $dockerfile === null )
			$dockerfile = 'Dockerfile';

		$this->requireProcess();

		$process = ProcessBuilder::create([
			'docker', 'build', '-f', $dockerfile, '-t', $imageName, '.'
		])
			->setTimeout(null)->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);

		if($process->getExitCode() !== 0)
			throw new BuildFailedException($imageName, $dockerfile, 22);

	}

	/**
	 * @param $username
	 * @param $password
	 */
	public function login($username, $password) {

		$this->requireProcess();

		$process = ProcessBuilder::create([
			'docker', 'login', '-u', $username, '-p', $password
		])
			->setTimeout(null)->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);

		if($process->getExitCode() !== 0)
			throw new LoginFailedException("Loggin failed", 21);
	}

	/**
	 * @param string $imageName
	 * @param string $dockerfile
	 */
	public function push(string $imageName) {

		$this->requireProcess();

		$process = ProcessBuilder::create([
			'docker', 'push', $imageName
		])
			->setTimeout(null)->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if($process->getExitCode() !== 0)
			throw new PushFailedException($imageName, 20);
	}

}