<?php

namespace Rancherize\Blueprint\Infrastructure\Service\Events;

use Rancherize\Blueprint\Infrastructure\Service\Services\LaravelQueueWorker;
use Rancherize\Configuration\Configuration;
use Symfony\Component\EventDispatcher\Event;

class QueueWorkerBuiltEvent extends Event
{
	const NAME = 'queue.worker.built';

	/** @var \Rancherize\Blueprint\Infrastructure\Service\Services\LaravelQueueWorker */
	private $queueWorker;
	/** @var \Rancherize\Configuration\Configuration */
	private $environmentConfiguration;
	/** @var \Rancherize\Configuration\Configuration */
	private $queueConfiguration;

	public function __construct(
		LaravelQueueWorker $queueWorker,
		Configuration $environmentConfiguration,
		Configuration $queueConfiguration
	) {
		$this->queueWorker = $queueWorker;
		$this->environmentConfiguration = $environmentConfiguration;
		$this->queueConfiguration = $queueConfiguration;
	}

	public function getQueueWorker(): \Rancherize\Blueprint\Infrastructure\Service\Services\LaravelQueueWorker
	{
		return $this->queueWorker;
	}

	public function getEnvironmentConfiguration(): \Rancherize\Configuration\Configuration
	{
		return $this->environmentConfiguration;
	}

	public function getQueueConfiguration(): \Rancherize\Configuration\Configuration
	{
		return $this->queueConfiguration;
	}


}