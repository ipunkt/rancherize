<?php

use Rancherize\Blueprint\Commands\BlueprintAdd;
use Rancherize\Blueprint\Commands\BlueprintList;

return [
	Rancherize\Commands\InitCommand::class,
	Rancherize\Commands\StartCommand::class,
	Rancherize\Commands\StopCommand::class,
	Rancherize\Commands\BuildCommand::class,
	Rancherize\Commands\PushCommand::class,
	Rancherize\Commands\ValidateCommand::class,
	Rancherize\Commands\RancherAccessCommand::class,
	Rancherize\Commands\EnvironmentSetCommand::class,
	Rancherize\Commands\EnvironmentVersionCommand::class,
	Rancherize\Plugin\Commands\PluginInstallCommand::class,
	Rancherize\Plugin\Commands\PluginRegisterCommand::class,
	BlueprintAdd::class,
	BlueprintList::class,
];
