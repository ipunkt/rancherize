<?php namespace Rancherize\Blueprint\Scheduler\SchedulerParser;

use Rancherize\Blueprint\Infrastructure\Service\Service;
use Rancherize\Blueprint\Scheduler\SchedulerExtraInformation\SchedulerExtraInformation;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\PrefixConfigurationDecorator;

/**
 * Class SchedulerParser
 * @package Rancherize\Blueprint\Scheduler
 */
class SchedulerParser {

	/**
	 * @param Service $service
	 * @param Configuration $configuration
	 */
	public function parse( Service $service, Configuration $configuration ) {
		$schedulerInformation = new SchedulerExtraInformation();
		$schedulerConfig = new PrefixConfigurationDecorator($configuration, 'scheduler.');

		if( !$configuration->has('scheduler') )
			return;

		if( !$schedulerConfig->get('enable', true) )
			return;

		$requiredTags = $schedulerConfig->get('tags', []);
		if( !is_array($requiredTags) )
			$requiredTags = [];
		$schedulerInformation->setRequireTags($requiredTags);

		$forbiddenTags = $schedulerConfig->get('forbid-tags', []);
		if( !is_array($forbiddenTags) )
			$forbiddenTags = [];
		$schedulerInformation->setForbidTags($forbiddenTags);

		$shouldHaveTags = $schedulerConfig->get('should-have-tags', []);
		if( !is_array($shouldHaveTags) )
			$shouldHaveTags = [];
		$schedulerInformation->setShouldHaveTags($shouldHaveTags);

		$shouldNotTags = $schedulerConfig->get('should-not-tags', []);
		if( !is_array($shouldNotTags) )
			$shouldNotTags = [];
		$schedulerInformation->setShouldNotTags($shouldNotTags);

		$allowSameHost = $schedulerConfig->get('same-host', false);
		$schedulerInformation->setAllowSameHost($allowSameHost);

		$scheduler = $schedulerConfig->get('scheduler', 'rancher');
		$schedulerInformation->setScheduler($scheduler);

		$service->addExtraInformation( $schedulerInformation );
	}
}