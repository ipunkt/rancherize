<?php namespace Rancherize\Validation\ForceResourceLimits;

use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Events\ValidatingEvent;

/**
 * Class EventHandler
 * @package Rancherize\Validation\ForceResourceLimits
 */
class EventHandler {

	/**
	 * @var bool
	 */
	protected $forceResourceLimits = false;

	/**
	 * @var string[]
	 */
	protected $reason = [];

	/**
	 * @param bool $forceResourceLimits
	 * @param string $reason
	 * @return EventHandler
	 */
	public function setForceResourceLimits( bool $forceResourceLimits, string $reason ) {
		$this->forceResourceLimits = $forceResourceLimits;

		if(!$forceResourceLimits)
			unset($this->reason[$reason]);
		else
			$this->reason[$reason] = $reason;

		return $this;
	}

	public function validate( ValidatingEvent $e ) {

		die( 'force resource limit: '. $this->forceResourceLimits );
		if ( !$this->forceResourceLimits )
			return;

		$configuration = $e->getConfiguration();
		if (!$configuration->has('resource-limit') )
			throw new ValidationFailedException([ 'resource-limit' => 'Missing, but required according to resource-limit settings ('.implode(', ', $this->reason).')' ]);

	}
}