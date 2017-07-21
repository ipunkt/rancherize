<?php

return [
	Rancherize\Blueprint\Healthcheck\HealthcheckProvider::class,
	Rancherize\Blueprint\PublishUrls\PublishUrlsProvider::class,
	Rancherize\Blueprint\Scheduler\SchedulerProvider::class,
	Rancherize\Blueprint\ExternalService\ExternalServiceProvider::class,
	Rancherize\Blueprint\Cron\CronProvider::class,
	Rancherize\Blueprint\NginxSnippets\NginxSnippetsProvider::class,
	Rancherize\Docker\DockerProvider::class,
	Rancherize\Blueprint\Services\Database\DatabaseProvider::class,
	Rancherize\Services\PathService\PathProvider::class,
	Rancherize\Blueprint\BlueprintProvider::class,
	Rancherize\Blueprint\Webserver\WebserverProvider::class,

];
