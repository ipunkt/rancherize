<?php


namespace Rancherize\Commands\Traits;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class IoCommandTrait
 * @package Rancherize\Commands\Traits
 *
 * Helps with the parameter spam within commands by setting $this->input and $this->output to InputInterface and OutputInterface
 */
trait IoCommandTrait {
	/**
	 * @var InputInterface
	 */
	protected $input;

	/**
	 * @var OutputInterface
	 */
	protected $output;

	protected function setIo(InputInterface $input, OutputInterface $output) {
		$this->input = $input;
		$this->output = $output;
	}
}