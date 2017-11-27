<?php namespace Rancherize\Push\CreateMode;

use Rancherize\Commands\Events\PushCommandCreateEvent;
use Rancherize\Commands\Events\PushCommandStartEvent;
use Rancherize\Configuration\Configuration;
use Rancherize\RancherAccess\RancherService;
use Rancherize\RancherAccess\UpgradeMode\RollingUpgradeChecker;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class StartCreateMode
 * @package Rancherize\Push\CreateMode
 */
class CreateCreateMode implements CreateMode {
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
		$startEvent = $this->makeCreateEvent( $serviceNames, $configuration );
		$this->eventDispatcher->dispatch( PushCommandCreateEvent::NAME, $startEvent );
		$serviceNames = $startEvent->getServiceNames();

		$rancherService->create( './.rancherize', $stackName, $serviceNames );
	}

	/**
	 * @param $serviceNames
	 * @param $config
	 * @return PushCommandStartEvent
	 */
	protected function makeCreateEvent( $serviceNames, $config ): PushCommandCreateEvent {
		$inServiceUpgradeEvent = new PushCommandCreateEvent();
		$inServiceUpgradeEvent->setServiceNames( $serviceNames );
		$inServiceUpgradeEvent->setConfiguration( $config );
		return $inServiceUpgradeEvent;
	}
}