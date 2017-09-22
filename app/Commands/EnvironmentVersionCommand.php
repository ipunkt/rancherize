<?php namespace Rancherize\Commands;

use Rancherize\Blueprint\Traits\BlueprintTrait;
use Rancherize\Commands\Traits\RancherTrait;
use Rancherize\Commands\Traits\ValidateTrait;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Traits\EnvironmentConfigurationTrait;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\RancherAccess\InServiceCheckerTrait;
use Rancherize\RancherAccess\NameMatcher\CompleteNameMatcher;
use Rancherize\RancherAccess\NameMatcher\PrefixNameMatcher;
use Rancherize\RancherAccess\RancherAccessService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 * @package Rancherize\Commands
 *
 * This command builds deployment files as if they were used in the start or push command.
 * Can be used to inspect the files for correctness before starting or pushing
 */
class EnvironmentVersionCommand extends Command implements LoadsConfiguration {

	use LoadsConfigurationTrait;
	use BlueprintTrait;
	use ValidateTrait;
	use RancherTrait;
	use EnvironmentConfigurationTrait;
	use InServiceCheckerTrait;

	protected function configure() {
		$this->setName('environment:version')
			->setDescription('Print the currently pushed version of the environment')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$configuration = $this->getConfiguration();
		$config = $this->environmentConfig($configuration, $environment);

		/**
		 * @var RancherAccessService $rancherConfiguration
		 */
		$rancherConfiguration = container('rancher-access-service');
		$rancherConfiguration->parse($configuration);
		$account = $rancherConfiguration->getAccount( $config->get('rancher.account') );

		$rancher = $this->getRancher();
		$rancher->setAccount($account)
			->setOutput($output);

		$stackName = $config->get('rancher.stack');
		$name = $config->get('service-name');

		$matcher = new PrefixNameMatcher($name);
		if( $this->inServiceChecker->isInService($config) )
			$matcher = new CompleteNameMatcher($name);

		$version = $rancher->getCurrentVersion($stackName, $matcher);

		$output->writeln($version);

		return 0;
	}


}