<?php namespace Rancherize\Blueprint\Infrastructure\Dockerfile;
use Rancherize\File\FileWriter;

/**
 * Class DockerfileWriter
 * @package Rancherize\Blueprint\Infrastructure\Dockerfile
 *
 * Write a dockefile to disk
 */
class DockerfileWriter {
	/**
	 * @var string
	 */
	private $path;

	/**
	 * @param Dockerfile $dockerfile
	 * @param FileWriter $writer
	 */
	public function write(Dockerfile $dockerfile, FileWriter $writer) {
		$lines = [
		];

		$lines[] = "FROM ".$dockerfile->getFrom();

		foreach($dockerfile->getVolumes() as $volume)
			$lines[] = "VOLUME $volume";

		$user = $dockerfile->getUser();
		$group = $dockerfile->getGroup();

		if( !empty($user) ) {
			$groupPart = ':'.$group;
			if( empty($group) )
				$groupPart = '';

			$lines[] = 'USER '.$user.$groupPart;
		}

		foreach($dockerfile->getCopies() as $from => $target)
			$lines[] = "COPY [\"$from\", \"$target\"]";

		$workdir = $dockerfile->getWorkdir();
		if( !empty($workdir) )
			$lines[] = "WORKDIR ". $workdir;

		foreach($dockerfile->getRunCommands() as $command)
			$lines[] = "RUN $command";

		$command = $dockerfile->getCommand();
		if( !empty($command) )
			$lines[] = "CMD ". $command;

		$entrypoint = $dockerfile->getEntrypoint();
		if( !empty($entrypoint) )
			$lines[] = "ENTRYPOINT ". $entrypoint;

		$writer->put($this->path.'Dockerfile', implode("\n", $lines));
	}

	/**
	 * @param string $path
	 * @return DockerfileWriter
	 */
	public function setPath(string $path): DockerfileWriter {
		$this->path = $path;
		return $this;
	}
}
