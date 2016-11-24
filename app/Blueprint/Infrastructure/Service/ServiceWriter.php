<?php namespace Rancherize\Blueprint\Infrastructure\Service;
use Rancherize\File\FileWriter;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ServiceWriter
 * @package Rancherize\Blueprint\Infrastructure\Service
 *
 * Add services to docker-compose.yml files
 */
class ServiceWriter {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @param Service $service
	 * @param FileWriter $fileWriter
	 *
	 * Append the given service to the path/docker-compose.yml and path/rancher-compose.yml
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
			$volumesFrom[] = $value->getName();
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
		if($service->getRestart() == Service::RESTART_START_ONCE)
			$labels['io.rancher.container.start_once'] = 'true';

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

	/**
	 * Only add the given option if the value is not empty
	 *
	 * @param $name
	 * @param $value
	 * @param $content
	 */
	protected function addNonEmpty($name, $value, &$content) {
		if( !empty($value) )
			$content[$name] = $value;
	}

	/**
	 * Clear the written files.
	 * Necessary because the write function appends to them so if fresh files are expected they have to be cleared first
	 *
	 * @param FileWriter $fileWriter
	 */
	public function clear(FileWriter $fileWriter) {
		$fileWriter->put($this->path.'docker-compose.yml', '');
		$fileWriter->put($this->path.'rancher-compose.yml', '');
	}

	/**
	 * Set a path to prefix before the *-compose files
	 *
	 * @param string $path
	 * @return ServiceWriter
	 */
	public function setPath(string $path): ServiceWriter {
		$this->path = $path;
		return $this;
	}
}