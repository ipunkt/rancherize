<?php namespace Rancherize\Push\Modes\InServiceUpgrade;

use Rancherize\Commands\Events\PushCommandInServiceUpgradeEvent;
use Rancherize\Configuration\Configuration;
use Rancherize\Push\Modes\PushMode;
use Rancherize\RancherAccess\Exceptions\NameNotFoundException;
use Rancherize\RancherAccess\HealthStateMatcher;
use Rancherize\RancherAccess\NameMatcher\CompleteNameMatcher;
use Rancherize\RancherAccess\RancherService;
use Rancherize\RancherAccess\StateInMatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class InServicePushMode
 * @package Rancherize\Push\Modes\InServiceUpgrade
 */
class InServicePushMode implements PushMode {
	/**
	 * @var EventDispatcher
	 */
	private $eventDispatcher;

	/**
	 * InServicePushMode constructor.
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct( EventDispatcher $eventDispatcher) {
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * @param Configuration $configuration
	 * @param $stackName
	 * @param $serviceName
	 * @param $version
	 * @param RancherService $rancherService
	 * @return mixed
	 */
	public function push( Configuration $configuration, string $stackName, string  $serviceName, string $version, RancherService $rancherService ) {

		$matcher = new CompleteNameMatcher($serviceName);

		/**
		 * Throw NoActiveServiceException, causing the service to be created
		 */
		$rancherService->getActiveService($stackName, $matcher);

		$serviceNames = [$serviceName];
		$startEvent = $this->makeInServiceEvent( $serviceNames, $configuration );
		$this->eventDispatcher->dispatch( PushCommandInServiceUpgradeEvent::NAME, $startEvent );
		$serviceNames = $startEvent->getServiceNames();
		$forcedUpgrade = $startEvent->isForceUpgrade();
		$serviceNames = array_unique($serviceNames);

		$rancherService->start( './.rancherize', $stackName, $serviceNames, true, $forcedUpgrade );

		// Use default Matcher
        $stateMatcher = new StateInMatcher(['upgraded', 'active']);
		if ( $configuration->get( 'rancher.upgrade-healthcheck', false ) )
			$stateMatcher = new HealthStateMatcher( 'healthy' );

		foreach($serviceNames as $serviceName) {

            try {
                $rancherService->wait($stackName, $serviceName, $stateMatcher);
                $rancherService->confirm('./.rancherize', $stackName, [$serviceName]);
            } catch (NameNotFoundException $e) {
                // Name not found means the stack either got deleted or it is a sidekick which internally has the main
                // service prefixed
            }
			// TODO: set timeout and roll back the upgrade if the timeout is reached without health confirmation.

		}
		return array($serviceNames, $startEvent);

	}

	/**
	 * @param $serviceNames
	 * @param $config
	 * @return PushCommandInServiceUpgradeEvent
	 */
	protected function makeInServiceEvent( $serviceNames, $config ): PushCommandInServiceUpgradeEvent {
		$inServiceUpgradeEvent = new PushCommandInServiceUpgradeEvent();
		$inServiceUpgradeEvent->setServiceNames( $serviceNames );
		$inServiceUpgradeEvent->setConfiguration( $config );
        $inServiceUpgradeEvent->setForceUpgrade(true);
		return $inServiceUpgradeEvent;
	}

}