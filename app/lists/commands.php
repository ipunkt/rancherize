<?php

use Rancherize\Blueprint\Commands\BlueprintAdd;
use Rancherize\Blueprint\Commands\BlueprintList;

return [
	Rancherize\Commands\RancherAccessCommand::class,
	Rancherize\Commands\EnvironmentSetCommand::class,
	Rancherize\Plugin\Commands\PluginInstallCommand::class,
	Rancherize\Plugin\Commands\PluginRegisterCommand::class,
	BlueprintAdd::class,
	BlueprintList::class,
];
