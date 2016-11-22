<?php namespace Rancherize\Blueprint;
use Rancherize\Blueprint\Infrastrukture\Infrastructure;
use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Configuration\ArrayConfiguration;
use Rancherize\Configuration\Configurable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface Blueprint
 * @package Rancherize\Blueprint
 */
interface Blueprint {

	/**
	 * @param string $flag
	 * @param $value
	 */
	function setFlag(string $flag, $value);

	/**
	 * @param Configurable $configurable
	 * @param string $environment
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return
	 */
	function init(Configurable $configurable, string $environment, InputInterface $input, OutputInterface $output);

	/**
	 * @param Configurable $configurable
	 * @param string $environment
	 * @throws ValidationFailedException
	 */
	function validate(Configurable $configurable, string $environment);

	/**
	 * @param Configurable $configurable
	 * @param string $environment
	 * @return Infrastructure
	 */
	function build(Configurable $configurable, string $environment);
}