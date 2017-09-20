<?php namespace Rancherize\Blueprint\Services\Mailtrap\MailtrapService;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Configuration\Configuration;

/**
 * Class MailtrapService
 */
class MailtrapService {

	/**
	 * @param Configuration $config
	 * @param Service $mainService
	 * @param \Rancherize\Blueprint\Infrastructure\Infrastructure $infrastructure
	 */
	public function parse( Configuration $config, Service $mainService, \Rancherize\Blueprint\Infrastructure\Infrastructure $infrastructure ) {

		$notEnabled = !$config->get( 'mailtrap.enable', true );
		if(!$config->has('mailtrap') || $notEnabled )
			return;

		$service = new Service();
		$service->setName('Mailtrap');
		$service->setImage('eaudeweb/mailtrap');
		$service->expose(80, $config->get('mailtrap.port') );

		$mainService->addLink($service, 'mail');

		$infrastructure->addService($service);

	}

}