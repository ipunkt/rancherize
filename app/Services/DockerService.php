<?php namespace Rancherize\Services;
use Rancherize\Docker\Exceptions\BuildFailedException;
use Rancherize\Docker\Exceptions\LoginFailedException;
use Rancherize\Docker\Exceptions\PushFailedException;
use Rancherize\Docker\Exceptions\StartFailedException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class DockerService
 * @package Rancherize\Services
 *
 * Capsulates access to docker commands
 */
class DockerService {

	use ProcessTrait;

	/**
	 * Build the given image using the given dockerfile or 'Dockerfile' if none is given
	 *
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
	 * login to Dockerhub using the given username and password
	 *
	 * @param $username
	 * @param $password
	 * @param string|null $server
	 */
	public function login($username, $password, $server = null) {

		$this->requireProcess();

		$commandArguments = [
			'docker', 'login', '-u', $username, '-p', $password
		];
		if( !empty($server) )
			$commandArguments[] = $server;
		$process = ProcessBuilder::create( $commandArguments )
			->setTimeout(null)->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_VERY_VERBOSE);

		if($process->getExitCode() !== 0)
			throw new LoginFailedException("Loggin failed", 21);
	}

	/**
	 * Push the given image to dockerhub. You will most likely need to login before using this
	 *
	 * @param string $imageName
	 * @param string|null $server
	 */
	public function push(string $imageName, string $server = null) {

		$this->requireProcess();

		$process = ProcessBuilder::create([
			'docker', 'push', $imageName
		])
			->setTimeout(null)->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if($process->getExitCode() !== 0)
			throw new PushFailedException($imageName, 20);
	}

	/**
	 * Start the infrastructure built in directory as projectName
	 *
	 * @param string $directory
	 * @param string $projectName
	 */
	public function start(string $directory, string $projectName) {

		$this->requireProcess();

		$process = ProcessBuilder::create([
			'docker-compose', '-p', $projectName, '-f', $directory.'/docker-compose.yml', 'up', '-d'
		])
			->setTimeout(null)->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if($process->getExitCode() !== 0)
			throw new StartFailedException($projectName);
	}

	/**
	 * Stop the infrastructure built in the directory as projectName
	 *
	 * @param string $directory
	 * @param string $projectName
	 */
	public function stop($directory, $projectName) {

		$this->requireProcess();

		$process = ProcessBuilder::create([
			'docker-compose', '-p', $projectName, '-f', $directory.'/docker-compose.yml', 'stop'
		])
			->setTimeout(null)->getProcess();

		$this->processHelper->run($this->output, $process, null, null, OutputInterface::VERBOSITY_NORMAL);
		if($process->getExitCode() !== 0)
			throw new StartFailedException($projectName);
	}

}