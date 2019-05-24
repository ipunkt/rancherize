<?php namespace Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\Rancher\V2;

use Rancherize\Blueprint\Scheduler\SchedulerExtraInformation\SchedulerExtraInformation;
use Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\Rancher\RancherTagService;
use Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\SchedulerWriterVersion;

/**
 * Class V2RancherSchedulerYamlWriter
 * @package Rancherize\Blueprint\Scheduler\SchedulerYamlWriter\Rancher\V2
 */
class V2RancherSchedulerYamlWriter implements SchedulerWriterVersion {
	/**
	 * @var RancherTagService
	 */
	private $tagService;

	/**
	 * V2RancherSchedulerYamlWriter constructor.
	 * @param RancherTagService $tagService
	 */
	public function __construct( RancherTagService $tagService) {
		$this->tagService = $tagService;
	}

	/**
	 * @param SchedulerExtraInformation $information
	 * @param $dockerContent
	 */
	public function write( SchedulerExtraInformation $information, &$dockerContent ) {
		if( !array_key_exists('labels', $dockerContent) )
			$dockerContent['labels'] = [];

		if( !$information->isAllowSameHost() )
			$dockerContent['labels']['io.rancher.scheduler.affinity:container_label_ne'] = 'io.rancher.stack_service.name=$${stack_name}/$${service_name}';
		else
            $dockerContent['labels']['io.rancher.scheduler.affinity:container_label_soft_ne'] = 'io.rancher.stack_service.name=$${stack_name}/$${service_name}';

		$requiredTags = $information->getRequireTags();
		if( !empty($requiredTags) ) {
			$tags = $this->tagService->makeTags($requiredTags);

			$dockerContent['labels']['io.rancher.scheduler.affinity:host_label'] = implode(',', $tags);
		}

		$forbiddenTags = $information->getForbidTags();
		if( !empty($forbiddenTags) ) {
			$tags = $this->tagService->makeTags($forbiddenTags);

			$dockerContent['labels']['io.rancher.scheduler.affinity:host_label_ne'] = implode(',', $tags);
		}

		$shouldHaveTags = $information->getShouldHaveTags();
		if( !empty($shouldHaveTags) ) {
			$tags = $this->tagService->makeTags($shouldHaveTags);

			$dockerContent['labels']['io.rancher.scheduler.affinity:host_label_soft'] = implode(',', $tags);
		}

		$shouldNotTags = $information->getShouldNotTags();
		if( !empty($shouldNotTags) ) {
			$tags = $this->tagService->makeTags($shouldNotTags);

			$dockerContent['labels']['io.rancher.scheduler.affinity:host_label_soft_ne'] = implode(',', $tags);
		}
	}
}