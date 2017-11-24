<?php namespace Rancherize\Push\Modes\ReplaceUpgrade;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\CreateModeFactory\CreateModeFactory;
use Rancherize\Push\Modes\PushMode;
use Rancherize\RancherAccess\Exceptions\NameNotFoundException;
use Rancherize\RancherAccess\NameMatcher\CompleteNameMatcher;
use Rancherize\RancherAccess\RancherService;
use Rancherize\RancherAccess\SingleStateMatcher;

/**
 * Class ReplacePushMode
 * @package Rancherize\Push\Modes\ReplaceUpgrade
 */
class ReplacePushMode implements PushMode {
	/**
	 * @var CreateModeFactory
	 */
	private $createModeFactory;

	/**
	 * ReplacePushMode constructor.
	 * @param CreateModeFactory $createModeFactory
	 */
	public function __construct( CreateModeFactory $createModeFactory) {
		$this->createModeFactory = $createModeFactory;
	}

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
		try {
			$rancherService->wait($stackName, $activeService, new SingleStateMatcher('none'));
		} catch(NameNotFoundException $e) {
			// We're actually waiting for this to happen. There is no `none` state
		}

		/**
		 * Create the service the way described in the config file
		 */
		$createMode = $this->createModeFactory->make($configuration);
		$createMode->create($configuration, $stackName, $serviceName, $version, $rancherService);

	}
}