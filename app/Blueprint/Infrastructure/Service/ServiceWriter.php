<?php namespace Rancherize\Blueprint\Infrastructure\Service;
use Rancherize\Blueprint\Infrastructure\Dockerfile\Dockerfile;
use Rancherize\Blueprint\Infrastructure\Dockerfile\DockerfileWriter;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterServicePreparedEvent;
use Rancherize\Blueprint\Infrastructure\Service\Events\ServiceWriterWritePreparedEvent;
use Rancherize\Configuration\Exceptions\FileNotFoundException;
use Rancherize\File\FileLoader;
use Rancherize\File\FileWriter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ServiceWriter
 * @package Rancherize\Blueprint\Infrastructure\Service
 *
 * Add services to docker-compose.yml files
 */
class ServiceWriter {
	/**
	 * @var FileLoader
	 */
	private $fileLoader;
	/**
	 * @var EventDispatcher
	 */
	private $event;
	/**
	 * @var DockerfileWriter
	 */
	private $dockerfileWriter;

    /**
     * ServiceWriter constructor.
     * @param FileLoader $fileLoader
     * @param EventDispatcher $event
     * @param DockerfileWriter $dockerfileWriter
     */
	public function __construct(FileLoader $fileLoader, EventDispatcher $event, DockerfileWriter $dockerfileWriter) {
		$this->fileLoader = $fileLoader;
		$this->event = $event;
		$this->dockerfileWriter = $dockerfileWriter;
    }

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

		$image = $service->getImage();
		if( $image instanceof Dockerfile ) {
			$dockerFileName = $service->getName().'-Dockerfile';

			$content['build'] = [
				// This is . because docker-compose already has its context in .rancherize
				'context' => '.',
				'dockerfile' => $dockerFileName,
			];
			$content['image'] = strtolower( $service->getName() );

			$this->dockerfileWriter->write($image, $fileWriter, $dockerFileName);
		} else
			$this->addNonEmpty('image', $service->getImage(), $content);

		$this->addNonEmpty('tty', $service->isTty(), $content);

		$networkMode = $service->getNetworkMode();
		$this->addNonEmpty( 'network_mode', $networkMode, $content );

		$environment = [];
		foreach($service->getEnvironmentVariables() as $name => $value)
			$environment[$name] = $value;
		$this->addNonEmpty('environment', $environment, $content);

		$ports = [];
		foreach($service->getExposedPorts() as $internal => $external)
			$ports[] = "$external:$internal";
		$this->addNonEmpty('ports', $ports, $content);

		$this->addNonEmpty('stdin_open', $service->isKeepStdin(), $content);
		$this->addNonEmpty('command', $service->getCommand(), $content);

		$volumes = [];
		foreach($service->getVolumeObjects() as $volume) {
			$volumeLine = $volume->getExternalPath().':'.$volume->getInternalPath();
			$mountOptions = $volume->getMountOptions();
			if( !empty($mountOptions) )
				$volumeLine .= ':'.implode(',', $mountOptions);
			$volumes[] = $volumeLine;
		}
		$this->addNonEmpty('volumes', $volumes, $content);

		$volumesFrom = [];
		foreach($service->getVolumesFrom() as $name => $value)
			$volumesFrom[] = $value->getName();
		$this->addNonEmpty('volumes_from', $volumesFrom, $content);

		$links = [];
		foreach($service->getLinks() as $name => $linkedService) {
			$serviceName = $linkedService->getName();
			if( is_string($name) )
				$links[] = "$serviceName:$name";
			else
				$links[] = "$serviceName";

		}
		$this->addNonEmpty('links', $links, $content);

		$workDir = $service->getWorkDir();
		if( !empty($workDir) )
			$content['working_dir'] = $workDir;

		$labels = [];
		foreach($service->getLabels() as $name => $value)
			$labels[$name] = $value;
		if($service->getRestart() == Service::RESTART_START_ONCE)
			$labels['io.rancher.container.start_once'] = 'true';

		$alwaysPulled = $service->isAlwaysPulled();
		if ( $alwaysPulled === Service::ALWAYS_PULLED_DEFAULT )
			$alwaysPulled = container( 'always-pulled-default' );
		if ( $alwaysPulled === Service::ALWAYS_PULLED_TRUE )
			$labels['io.rancher.container.pull_image'] = 'always';

		if( !empty($service->getSidekicks()) ) {

			$sidekickNames = [];
			foreach($service->getSidekicks() as $sidekickService)
				$sidekickNames[] = $sidekickService->getName();

			$labels['io.rancher.sidekicks'] = implode(',', $sidekickNames);

		}

		$this->addNonEmpty('labels', $labels, $content);

		$externalLinks = [];
		foreach($service->getExternalLinks() as $name => $serviceName) {

			/**
			 * Skip external link if internal link with the same name exists
			 */
			if ( is_string( $name ) && array_key_exists( $name, $service->getLinks() ) )
				continue;

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

		$volumeDefinitions = $this->buildVolumeDefinitions($service);

		$rancherContent = [
			'scale' => $service->getScale()
		];

		if( $service->isStartFirst() ) {
			$rancherContent['upgrade_strategy'] = [
				'start_first' => true
			];
		}

		$rancherContentPreparedEvent = new ServiceWriterServicePreparedEvent($service, $content, $volumeDefinitions, $rancherContent);
		$this->event->dispatch(ServiceWriterServicePreparedEvent::NAME, $rancherContentPreparedEvent);

		// might have been changed by the listeners
		$rancherContent = $rancherContentPreparedEvent->getRancherContent();
		$content = $rancherContentPreparedEvent->getDockerContent();
		$volumeDefinitions = $rancherContentPreparedEvent->getVolumeDefinition();


        $definition = ServiceYamlDefinition::make($content, $rancherContent, $volumeDefinitions);
        $this->event->dispatch(ServiceWriterWritePreparedEvent::NAME,
            new ServiceWriterWritePreparedEvent(
                $service,
                $definition
            )
        );

		$this->writeYaml($this->path . '/docker-compose.yml', $service, $fileWriter, $definition->dockerComposeEntry, $definition->volumeDefinition);
		$this->writeYaml($this->path . '/rancher-compose.yml', $service, $fileWriter, $definition->rancherComposeEntry);
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
		$fileWriter->put($this->path.'/docker-compose.yml', '');
		$fileWriter->put($this->path.'/rancher-compose.yml', '');
	}

	/**
	 * Set a path to prefix before the *-compose files
	 *
	 * @param string $path
	 * @return ServiceWriter
	 */
	public function setPath(string $path): ServiceWriter {

		// remove trailing '/' if found
		if( substr($path, -1) === '/' )
			$path = substr($path, 0, -1);

		$this->path = $path;
		return $this;
	}

	/**
	 * @param $targetFile
	 * @param Service $service
	 * @param FileWriter $fileWriter
	 * @param $content
	 * @param array $volumes
	 */
	protected function writeYaml($targetFile, Service $service, FileWriter $fileWriter, $content, array $volumes = null) {
		if( $volumes === null )
			$volumes = [];

		try {
			$dockerData = Yaml::parse($this->fileLoader->get($targetFile));
			if(!is_array($dockerData))
				$dockerData = [ 'version' => container('docker-compose.version') ];

			// handle v2 format
			if ( array_key_exists('version', $dockerData) ) {

				$dockerData['services'][$service->getName()] = $content;

				$this->addVolumes($dockerData, $volumes);

				/**
				 * Rancher version 1.2.2 produces rancher-compose.yaml files which rancher-compose does not read:
				 * Bug workaround: `line 25: cannot unmarshal !!map into []string`
				 */
				foreach( $dockerData['services'] as $key => &$service ) {

					if( array_key_exists('lb_config', $service) )
						unset($service['lb_config']);
				}

			} else
				$dockerData[$service->getName()] = $content;

		} catch (FileNotFoundException $e) {
			$dockerData = [$service->getName() => $content];
		}

		$dockerYaml = Yaml::dump($dockerData, 100, 2);


		$fileWriter->put($targetFile, $dockerYaml);
	}

	/**
	 * @param array $dockerData
	 * @param array $volumes
	 * @parama array $volumes
	 */
	private function addVolumes( array &$dockerData, array $volumes ) {

		if( empty($volumes) )
			return;

		if( !array_key_exists('volumes', $dockerData) )
			$dockerData['volumes'] = [];

		foreach($volumes as $volumeName => $volumeData)
			$dockerData['volumes'][$volumeName] = $volumeData;
	}

	/**
	 * @param Service $service
	 * @return array
	 */
	private function buildVolumeDefinitions( Service $service ) {

		$volumeDefinitions = [];

		foreach($service->getVolumeObjects() as $volumeObject) {
			$driver = $volumeObject->getDriver();
			$options = $volumeObject->getOptions();

			if( empty($driver) )
				continue;

			$volumeDefinition = [
				'driver' => $driver,
			];

			if( is_array($options) && !empty($options) )
				$volumeDefinition['driver_opts'] = $options;

            if ($volumeObject->isExternal()) {
                $volumeDefinition['external'] = true;
            }

			$volumeDefinitions[ $volumeObject->getExternalPath() ] = $volumeDefinition;

		}

		return $volumeDefinitions;
	}

}