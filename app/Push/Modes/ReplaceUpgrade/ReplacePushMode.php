<?php namespace Rancherize\Push\Modes\ReplaceUpgrade;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\Modes\PushMode;
use Rancherize\RancherAccess\NameMatcher\CompleteNameMatcher;
use Rancherize\RancherAccess\RancherService;

/**
 * Class ReplacePushMode
 * @package Rancherize\Push\Modes\ReplaceUpgrade
 */
class ReplacePushMode implements PushMode {

	/**
	 * @param Configuration $configuration
	 * @param $stackName
	 * @param $serviceName
	 * @param $version
	 * @param RancherService $rancherService
	 */
	public function push( Configuration $configuration, string $stackName, string $serviceName, string $version, RancherService $rancherService ) {

		$matcher = new CompleteNameMatcher($stackName);

		/**
		 * Throw NoActiveServiceException, causing the service to be created
		 */
		$activeService = $rancherService->getActiveService($stackName, $matcher);

		$rancherService->rm( './.rancherize', $stackName, [$activeService] );
		$rancherService->create( './.rancherize', $stackName, [$serviceName] );
	}
}