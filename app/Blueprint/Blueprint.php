<?php namespace Rancherize\Blueprint;
use Rancherize\Blueprint\Infrastructure\Infrastructure;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Configuration\Configurable;
use Rancherize\Configuration\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface Blueprint
 * @package Rancherize\Blueprint
 *
 * A Blueprint describes a use case for a docker app
 *
 */
interface Blueprint {

	/**
	 * Pass creation flags from the command.
	 * Currently only sets the 'dev' flag
	 *
	 * @param string $flag
	 * @param $value
	 */
	function setFlag(string $flag, $value);

	/**
	 * Fill the configurable with all possible options with explanatory default options set
	 *
	 * @param Configurable $configurable
	 * @param string $environment
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return
	 */
	function init(Configurable $configurable, string $environment, InputInterface $input, OutputInterface $output);

	/**
	 * Ensure that the given environment has at least the minimal configuration options set to start and deploy this
	 * blueprint
	 *
	 * @param Configuration $configurable
	 * @param string $environment
	 * @throws ValidationFailedException
	 */
	function validate(Configuration $configurable, string $environment);

	/**
	 * @param Configuration $configuration
	 * @param string $environment
	 * @param string $version
	 * @return Infrastructure
	 */
	function build(Configuration $configuration, string $environment, string $version = null) : Infrastructure;
}