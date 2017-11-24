<?php namespace Rancherize\Push\CreateMode;

use Rancherize\Commands\Events\PushCommandStartEvent;
use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\RancherService;
use Rancherize\RancherAccess\UpgradeMode\RollingUpgradeChecker;
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
	 * @var RollingUpgradeChecker
	 */
	private $rollingUpgradeChecker;

	/**
	 * StartCreateMode constructor.
	 * @param EventDispatcher $eventDispatcher
	 * @param RollingUpgradeChecker $rollingUpgradeChecker
	 * @internal param InServiceChecker $inServiceChecker
	 */
	public function __construct( EventDispatcher $eventDispatcher, RollingUpgradeChecker $rollingUpgradeChecker) {
		$this->eventDispatcher = $eventDispatcher;
		$this->rollingUpgradeChecker = $rollingUpgradeChecker;
	}

	/**
	 * @param Configuration $configuration
	 * @param string $stackName
	 * @param string $serviceName
	 * @param string $version
	 * @param RancherService $rancherService
	 */
	public function create( Configuration $configuration, string $stackName, string $serviceName, string $version, RancherService $rancherService ) {

		$versionizedName = $serviceName;
		if( $this->rollingUpgradeChecker->isRollingUpgrade($configuration) )
			$versionizedName = $serviceName.'-'.$version;

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