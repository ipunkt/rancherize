<?php namespace Rancherize\Push\Modes\RollingUpgrade;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\Modes\PushMode;
use Rancherize\RancherAccess\NameMatcher\PrefixNameMatcher;
use Rancherize\RancherAccess\RancherService;

/**
 * Class RollingPushMode
 * @package Rancherize\Push\Modes\RollingUpgrade
 */
class RollingPushMode implements PushMode {

	/**
	 * @param Configuration $configuration
	 * @param $stackName
	 * @param $serviceName
	 * @param $version
	 * @param RancherService $rancherService
	 */
	public function push( Configuration $configuration, string $stackName, string $serviceName, string $version, RancherService $rancherService ) {
		$matcher = new PrefixNameMatcher($serviceName);
		$activeStack = $rancherService->getActiveService($stackName, $matcher);

		$versionizedName = $serviceName.'-'.$version;
		$rancherService->upgrade( './.rancherize', $stackName, $activeStack, $versionizedName );
	}
}