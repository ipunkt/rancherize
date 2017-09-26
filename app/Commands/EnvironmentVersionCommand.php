<?php namespace Rancherize\Commands;

use Rancherize\Commands\Traits\ValidateTrait;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Services\EnvironmentConfigurationService;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\RancherAccess\InServiceCheckerTrait;
use Rancherize\RancherAccess\NameMatcher\CompleteNameMatcher;
use Rancherize\RancherAccess\NameMatcher\PrefixNameMatcher;
use Rancherize\RancherAccess\RancherAccessParsesConfiguration;
use Rancherize\RancherAccess\RancherAccessService;
use Rancherize\RancherAccess\RancherService;
use Rancherize\Services\BlueprintService;
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
	use ValidateTrait;

	use InServiceCheckerTrait;
	/**
	 * @var BlueprintService
	 */
	private $blueprintService;
	/**
	 * @var RancherAccessService
	 */
	private $rancherAccessService;
	/**
	 * @var EnvironmentConfigurationService
	 */
	private $environmentConfigurationService;
	/**
	 * @var RancherService
	 */
	private $rancherService;

	/**
	 * EnvironmentVersionCommand constructor.
	 * @param BlueprintService $blueprintService
	 * @param RancherAccessService $rancherAccessService
	 * @param EnvironmentConfigurationService $environmentConfigurationService
	 * @param RancherService $rancherService
	 */
	public function __construct( BlueprintService $blueprintService, RancherAccessService $rancherAccessService,
			EnvironmentConfigurationService $environmentConfigurationService, RancherService $rancherService) {
		parent::__construct();
		$this->blueprintService = $blueprintService;
		$this->rancherAccessService = $rancherAccessService;
		$this->environmentConfigurationService = $environmentConfigurationService;
		$this->rancherService = $rancherService;
	}

	protected function configure() {
		$this->setName('environment:version')
			->setDescription('Print the currently pushed version of the environment')
			->addArgument('environment', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$environment = $input->getArgument('environment');

		$configuration = $this->getConfiguration();
		$config = $this->environmentConfigurationService->environmentConfig($configuration, $environment);

		if( $this->rancherAccessService instanceof RancherAccessParsesConfiguration)
			$this->rancherAccessService->parse($configuration);
		$account = $this->rancherAccessService->getAccount( $config->get('rancher.account') );

		$rancher = $this->rancherService;
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