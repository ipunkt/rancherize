<?php


use Rancherize\Blueprint\DataImages\DataImageProvider;
use Rancherize\Blueprint\UpgradeAll\Provider;
use Rancherize\Validation\ValidationProvider;

return [

	Rancherize\File\FileProvider::class,
	Rancherize\Blueprint\Infrastructure\InfrastructureProvider::class,
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
	Rancherize\Blueprint\Services\Directory\DirectoryProvider::class,
	/**
	 * TODO: Only here to keep backwards compatibility. Remove with v3
	 */
	RancherizePhp53\Php53Provider::class,
	Rancherize\InputOutput\InputOutputProvider::class,
	Rancherize\Push\PushProvider::class,
	Rancherize\Blueprint\Network\NetworkProvider::class,
	Rancherize\Blueprint\PhpCommands\PhpCommandsProvider::class,
	Rancherize\Blueprint\ResourceLimit\ResourceLimitProvider::class,
	ValidationProvider::class,
    Rancherize\Services\Provider::class,
    DataImageProvider::class,
    Provider::class,

	Rancherize\Commands\CommandsProvider::class,

];
