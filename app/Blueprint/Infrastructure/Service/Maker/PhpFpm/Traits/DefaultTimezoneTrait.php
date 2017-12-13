<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;


use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\DefaultTimezone;

trait DefaultTimezoneTrait {


	/**
	 * @var string
	 */
	protected $defaultTimezone = DefaultTimezone::DEFAULT_TIMEZONE;

	/**
	 * Set the default php timezone
	 *
	 * @param $defaultTimezone
	 * @return $this
	 */
	public function setDefaultTimezone( $defaultTimezone ) {
		$this->defaultTimezone = $defaultTimezone;
		return $this;
	}

}