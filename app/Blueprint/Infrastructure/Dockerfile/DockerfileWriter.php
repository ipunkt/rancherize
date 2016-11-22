<?php namespace Rancherize\Blueprint\Infrastructure\Dockerfile;
use Rancherize\File\FileWriter;

/**
 * Class DockerfileWriter
 * @package Rancherize\Blueprint\Infrastructure\Dockerfile
 */
class DockerfileWriter {
	/**
	 * @var string
	 */
	private $path;

	/**
	 * DockerfileWriter constructor.
	 * @param $path
	 */
	public function __construct(string $path) {
		$this->path = $path;
	}

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

		foreach($dockerfile->getCopies() as $from => $target)
			$lines[] = "COPY [\"$from\", \"$target\"]";

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
}