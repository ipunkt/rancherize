<?php namespace Rancherize\Blueprint\Network\DefaultNetwork;

use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Infrastructure\Network\Network;
use Rancherize\Configuration\Configuration;

/**
 * Class DefaultNetworkParser
 * @package Rancherize\Blueprint\Network\DefaultNetwork
 */
class DefaultNetworkParser {

	public function parse( Configuration $configuration, Infrastructure $infrastructure ) {

		$networkName = $configuration->get('default-network');

		$defaultNetwork = new Network();
		$defaultNetwork->setName('default');
		$defaultNetwork->setExternal(true);
		$defaultNetwork->setExternalName($networkName);

		$infrastructure->addNetwork($defaultNetwork);

	}
}