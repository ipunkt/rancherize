<?php

return [
	'Rancherize\Blueprint\Healthcheck\HealthcheckProvider',
	'Rancherize\Blueprint\PublishUrls\PublishUrlsProvider',
	'Rancherize\Blueprint\Scheduler\SchedulerProvider',
	'Rancherize\Blueprint\ExternalService\ExternalServiceProvider',
	'Rancherize\Blueprint\Cron\CronProvider',
	'Rancherize\Blueprint\NginxSnippets\NginxSnippetsProvider',
	\Rancherize\Docker\DockerProvider::class,
	\Rancherize\Blueprint\Services\Database\DatabaseProvider::class,
	\Rancherize\Services\PathService\PathProvider::class,

];
