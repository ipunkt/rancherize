<?php namespace Rancherize\Blueprint;
use Rancherize\Configuration\Configurable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface Blueprint
 * @package Rancherize\Blueprint
 */
interface Blueprint {

	/**
	 * @param Configurable $configurable
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return
	 */
	function init(Configurable $configurable, InputInterface $input, OutputInterface $output);
}