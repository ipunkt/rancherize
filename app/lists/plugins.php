<?php

return [

	Rancherize\Blueprint\Healthcheck\HealthcheckProvider::class,
	Rancherize\Blueprint\PublishUrls\PublishUrlsProvider::class,
	Rancherize\Blueprint\Scheduler\SchedulerProvider::class,
	Rancherize\Blueprint\ExternalService\ExternalServiceProvider::class,
	Rancherize\Blueprint\Cron\CronProvider::class,
	Rancherize\Blueprint\NginxSnippets\NginxSnippetsProvider::class,
	Rancherize\Docker\DockerProvider::class,
	Rancherize\RancherAccess\RancherAccessProvider::class,
	Rancherize\Blueprint\Services\Database\DatabaseProvider::class,
	Rancherize\Services\PathService\PathProvider::class,
	Rancherize\Blueprint\BlueprintProvider::class,
	Rancherize\Blueprint\Webserver\WebserverProvider::class,
	Rancherize\Blueprint\Volumes\VolumesProvider::class,
  Rancherize\Configuration\ConfigurationProvider::class,
  Rancherize\EnvironmentAccessConfig\EnvironmentAccessConfigProvider::class,
	Rancherize\Blueprint\ProjectName\ProjectNameProvider::class,
	Rancherize\Configuration\Versions\VersionsProvider::class,
	Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PhpFpmProvider::class,
	Rancherize\Blueprint\Services\Mailtrap\MailtrapProvider::class,

];
