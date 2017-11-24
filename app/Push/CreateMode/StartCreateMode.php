<?php namespace Rancherize\Push\CreateMode;

use Rancherize\Commands\Events\PushCommandStartEvent;
use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\RancherService;
use Rancherize\RancherAccess\UpgradeMode\InServiceChecker;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class StartCreateMode
 * @package Rancherize\Push\CreateMode
 */
class StartCreateMode implements CreateMode {
	/**
	 * @var EventDispatcher
	 */
	private $eventDispatcher;
	/**
	 * @var InServiceChecker
	 */
	private $inServiceChecker;

	/**
	 * StartCreateMode constructor.
	 * @param EventDispatcher $eventDispatcher
	 * @param InServiceChecker $inServiceChecker
	 */
	public function __construct( EventDispatcher $eventDispatcher, InServiceChecker $inServiceChecker) {
		$this->eventDispatcher = $eventDispatcher;
		$this->inServiceChecker = $inServiceChecker;
	}

	/**
	 * @param Configuration $configuration
	 * @param string $stackName
	 * @param string $serviceName
	 * @param string $version
	 * @param RancherService $rancherService
	 */
	public function create( Configuration $configuration, string $stackName, string $serviceName, string $version, RancherService $rancherService ) {

		$versionizedName = $serviceName.'-'.$version;
		if( $this->inServiceChecker->isInService($configuration) )
			$versionizedName = $serviceName;

		$serviceNames = [$versionizedName];
		$startEvent = $this->makeStartEvent( $serviceNames, $configuration );
		$this->eventDispatcher->dispatch( PushCommandStartEvent::NAME, $startEvent );
		$serviceNames = $startEvent->getServiceNames();

		$rancherService->start( './.rancherize', $stackName, $serviceNames );
	}

	/**
	 * @param $serviceNames
	 * @param $config
	 * @return PushCommandStartEvent
	 */
	protected function makeStartEvent( $serviceNames, $config ): PushCommandStartEvent {
		$inServiceUpgradeEvent = new PushCommandStartEvent();
		$inServiceUpgradeEvent->setServiceNames( $serviceNames );
		$inServiceUpgradeEvent->setConfiguration( $config );
		return $inServiceUpgradeEvent;
	}
}