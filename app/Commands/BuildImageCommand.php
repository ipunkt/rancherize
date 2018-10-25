<?php namespace Rancherize\Commands;

use Rancherize\Commands\Traits\EventTrait;
use Rancherize\Commands\Traits\IoTrait;
use Rancherize\Commands\Types\RancherCommand;
use Rancherize\Configuration\Configuration;
use Rancherize\Configuration\LoadsConfiguration;
use Rancherize\Configuration\Services\EnvironmentConfigurationService;
use Rancherize\Configuration\Traits\LoadsConfigurationTrait;
use Rancherize\Docker\DockerAccessService;
use Rancherize\Docker\DockerAccount;
use Rancherize\Push\CreateModeFactory\CreateModeFactory;
use Rancherize\Push\ModeFactory\PushModeFactory;
use Rancherize\RancherAccess\RancherAccessService;
use Rancherize\RancherAccess\RancherService;
use Rancherize\RancherAccess\UpgradeMode\ReplaceUpgradeChecker;
use Rancherize\Services\BlueprintService;
use Rancherize\Services\BuildService;
use Rancherize\Services\DockerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BuildImageCommand
 * @package Rancherize\Commands
 *
 * Build the image for the given environment and push it to the given registry
 */
class BuildImageCommand extends Command implements LoadsConfiguration, RancherCommand
{

    use IoTrait;
    use LoadsConfigurationTrait;
    use EventTrait;

    /**
     * @var RancherAccessService
     */
    private $rancherAccessService;

    /**
     * @var DockerService
     */
    private $dockerService;
    /**
     * @var BuildService
     */
    private $buildService;

    /**
     * @var BlueprintService
     */
    private $blueprintService;
    /**
     * @var EnvironmentConfigurationService
     */
    private $environmentConfigurationService;
    /**
     * @var DockerAccessService
     */
    private $dockerAccessService;
    /**
     * @var RancherService
     */
    private $rancherService;

    /**
     * @var ReplaceUpgradeChecker
     */
    private $replaceUpgradeChecker;
    /**
     * @var PushModeFactory
     */
    private $pushModeFactory;
    /**
     * @var CreateModeFactory
     */
    private $createModeFactory;

    /**
     * PushCommand constructor.
     * @param RancherAccessService $rancherAccessService
     * @param DockerService $dockerService
     * @param BuildService $buildService
     * @param BlueprintService $blueprintService
     * @param EnvironmentConfigurationService $environmentConfigurationService
     * @param DockerAccessService $dockerAccessService
     * @param RancherService $rancherService
     * @param ReplaceUpgradeChecker $replaceUpgradeChecker
     * @param PushModeFactory $pushModeFactory
     * @param CreateModeFactory $createModeFactory
     */
    public function __construct(
        RancherAccessService $rancherAccessService,
        DockerService $dockerService,
        BuildService $buildService,
        BlueprintService $blueprintService,
        EnvironmentConfigurationService $environmentConfigurationService,
        DockerAccessService $dockerAccessService,
        RancherService $rancherService,
        ReplaceUpgradeChecker $replaceUpgradeChecker,
        PushModeFactory $pushModeFactory,
        CreateModeFactory $createModeFactory

    ) {
        parent::__construct();
        $this->rancherAccessService = $rancherAccessService;
        $this->dockerService = $dockerService;
        $this->buildService = $buildService;
        $this->blueprintService = $blueprintService;
        $this->environmentConfigurationService = $environmentConfigurationService;
        $this->dockerAccessService = $dockerAccessService;
        $this->rancherService = $rancherService;
        $this->replaceUpgradeChecker = $replaceUpgradeChecker;
        $this->pushModeFactory = $pushModeFactory;
        $this->createModeFactory = $createModeFactory;
    }

    protected function configure()
    {
        $this->setName('build-image')
            ->setDescription('Start or upgrade the given environment in Rancher')
            ->addArgument('environment', InputArgument::REQUIRED)
            ->addArgument('version');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $this->setIo($input, $output);

        $environment = $this->getEnvironment($input);
        $version = $input->getArgument('version') ?: 'latest';

        $configuration = $this->getConfiguration();
        $environmentConfig = $this->environmentConfigurationService->environmentConfig($configuration, $environment);

        $this->buildService->createDockerCompose('');
        $this->buildService->createRancherCompose('');

        $repository = $environmentConfig->get('docker.repository');
        $versionPrefix = $environmentConfig->get('docker.version-prefix', '');

        $image = $repository . ':' . $versionPrefix . $version;

        $dockerAccount = $this->login($configuration, $environmentConfig);

        $blueprint = $this->blueprintService->byConfiguration($configuration, $input->getArguments());
        $this->buildService
            ->setVersion($version)
            ->setDockerAccount($dockerAccount)
            ->build($blueprint, $configuration, $environment, true);

        $dockerService = $this->dockerService;
        $dockerService->setOutput($output)
            ->setProcessHelper($this->getHelper('process'));

        $this->buildImage($dockerService, $image, $dockerAccount);

        return 0;
    }

    /**
     * Return the environment name to be loaded
     *
     * @param InputInterface $input
     * @return string
     */
    public function getEnvironment(InputInterface $input)
    {
        return $input->getArgument('environment');
    }

    protected function login(Configuration $configuration, Configuration $config)
    {

        $dockerAccessService = $this->dockerAccessService;
        $dockerAccessService->parse($configuration);
        $dockerAccount = $dockerAccessService->getAccount($config->get('docker.account'));

        return $dockerAccount;

    }

    /**
     * @param InputInterface $input
     * @param DockerService $dockerService
     * @param Configuration $configuration
     * @param Configuration $config
     * @param $image
     * @internal param $dockerAccount
     */
    protected function buildImage(DockerService $dockerService, $image, DockerAccount $dockerAccount)
    {

        if ($this->getInput()->getOption('image-exists')) {
            $this->getOutput()->writeln("Option image-exists was set, skipping build.",
                OutputInterface::VERBOSITY_VERBOSE);

            return;
        }


        $server = $dockerAccount->getServer();
        if (!empty($server)) {
            $serverHost = parse_url($server, PHP_URL_HOST);
            $image = $serverHost . '/' . $image;
        }

        $dockerService->build($image, './.rancherize/Dockerfile');
        $dockerService->login($dockerAccount->getUsername(), $dockerAccount->getPassword(),
            $dockerAccount->getServer());
        $dockerService->push($image);
    }
}
