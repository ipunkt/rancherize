<?php namespace Rancherize\Blueprint\Services\Mailtrap;

use Rancherize\Blueprint\Services\Mailtrap\MailtrapService\MailtrapService;
use Rancherize\Plugin\Provider;

/**
 * Class MailtrapProvider
 */
class MailtrapProvider implements Provider {

	use \Rancherize\Plugin\ProviderTrait;

	/**
	 */
	public function register() {
		$this->container['mailtrap-service'] = function() {
			return new MailtrapService();
		};
	}

	/**
	 */
	public function boot() {
	}
}