<?php namespace Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\Rancher;

/**
 * Class RancherTagService
 * @package Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\Rancher
 *
 * Coverts arrays to "$key=$value" / "$value=true" which then can be imploded for rancher scheduling rules
 */
class RancherTagService {

	/**
	 * @param array $tags
	 */
	public function makeTags( array $keyValueTags ) {

		$tags = [];
		foreach($keyValueTags as $tag => $value) {
			if( is_numeric($tag) ) {
				$tag = $value;
				$value = 'true';
			}

			$tags[] = "$tag=$value";

		}

		return $tags;
	}
}