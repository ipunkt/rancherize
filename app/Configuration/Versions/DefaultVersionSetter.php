<?php namespace Rancherize\Configuration\Versions;

use Rancherize\Commands\Events\InitCommandEvent;

/**
 * Class DefaultVersionSetter
 * @package Rancherize\Configuration\Versions
 *
 * Sets the default version on init. Unless a version is already set
 */
class DefaultVersionSetter {

	/**
	 * @param InitCommandEvent $event
	 */
	public function initEvent( InitCommandEvent $event ) {

		$configuration = $event->getConfiguration();

		if( !$configuration->has('project.version') )
			$configuration->set('project.version', 3);

	}

}