<?php namespace Rancherize\Blueprint\Infrastructure\Service;
use Rancherize\File\FileWriter;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ServiceWriter
 * @package Rancherize\Blueprint\Infrastructure\Service
 */
class ServiceWriter {

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
	 * @param Service $service
	 * @param FileWriter $fileWriter
	 */
	public function write(Service $service, FileWriter $fileWriter) {
		$content = [];

		$this->addNonEmpty('image', $service->getImage(), $content);
		$this->addNonEmpty('tty', $service->isTty(), $content);

		$environment = [];
		foreach($service->getEnvironmentVariables() as $name => $value)
			$environment[$name] = $value;
		$this->addNonEmpty('environment', $environment, $content);

		$ports = [];
		foreach($service->getExposedPorts() as $internal => $external)
			$ports[] = "$external:$internal";
		$this->addNonEmpty('ports', $ports, $content);

		$this->addNonEmpty('stdin_open', $service->isKeepStdin(), $content);

		$volumes = [];
		foreach($service->getVolumes() as $name => $value)
			$volumes[] = "$name:$value";
		$this->addNonEmpty('volumes', $volumes, $content);

		$volumesFrom = [];
		foreach($service->getVolumesFrom() as $name => $value)
			$volumesFrom[] = "$name:$value";
		$this->addNonEmpty('volumes_from', $volumesFrom, $content);

		$labels = [];
		foreach($service->getLabels() as $name => $value)
			$labels[$name] = $value;
		$this->addNonEmpty('labels', $labels, $content);

		$links = [];
		foreach($service->getLinks() as $name => $linkedService) {
			$serviceName = $linkedService->getName();
			if( is_string($name) )
				$links[] = "$serviceName:$name";
			else
				$links[] = "$serviceName";

		}
		$this->addNonEmpty('links', $links, $content);

		$labels = [];
		foreach($service->getLinks() as $name => $value)
			$labels[$name] = $value;

		if( !empty($service->getSidekicks()) ) {

			$sidekickNames = [];
			foreach($service->getSidekicks() as $sidekickService)
				$sidekickNames[] = $sidekickService->getName();

			$labels['io.rancher.sidekicks'] = implode(',', $sidekickNames);

		}

		$this->addNonEmpty('labels', $labels, $content);

		$externalLinks = [];
		foreach($service->getExternalLinks() as $name => $serviceName) {

			if( is_string($name) )
				$externalLinks[] = "$serviceName:$name";
			else
				$externalLinks[] = "$serviceName";

		}
		$this->addNonEmpty('external_links', $externalLinks, $content);

		$restartValues = [
			Service::RESTART_UNLESS_STOPPED => 'unless-stopped',
			Service::RESTART_AWAYS => 'always',
			Service::RESTART_NEVER => 'no',
			Service::RESTART_START_ONCE => 'no',
		];
		$content['restart'] = $restartValues[ $service->getRestart() ];


		$dockerYaml = Yaml::dump([$service->getName() => $content], 100, 2);

		$fileWriter->append($this->path.'docker-compose.yml', $dockerYaml);

		$rancherContent = [
			'scale' => $service->getScale()
		];
		$rancherYaml = Yaml::dump([$service->getName() => $rancherContent], 100, 2);

		$fileWriter->append($this->path.'rancher-compose.yml', $rancherYaml);
	}

	protected function addNonEmpty($name, $value, &$content) {
		if( !empty($value) )
			$content[$name] = $value;
	}

	public function clear(FileWriter $fileWriter) {
		$fileWriter->put($this->path.'docker-compose.yml', '');
		$fileWriter->put($this->path.'rancher-compose.yml', '');
	}

	private function buildStringArray($getLinks) {
	}
}