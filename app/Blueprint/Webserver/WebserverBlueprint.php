<?php namespace Rancherize\Blueprint\Webserver;
use Rancherize\Blueprint\Blueprint;
use Rancherize\Configuration\Configurable;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WebserverBlueprint
 * @package Rancherize\Blueprint\Webserver
 */
class WebserverBlueprint implements Blueprint {

	/**
	 * @param Configurable $configurable
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return
	 */
	public function init(Configurable $configurable, InputInterface $input, OutputInterface $output) {
		$output->writeln("Hi from the Webserver Blueprint!");
	}
}