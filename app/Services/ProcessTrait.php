<?php


namespace Rancherize\Services;


use Rancherize\Exceptions\Exception;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;

trait ProcessTrait {

	/**
	 * @var ProcessHelper
	 */
	protected $processHelper = null;

	/**
	 * @var OutputInterface
	 */
	protected $output = null;

	protected function requireProcess() {
		if($this->processHelper === null)
			throw new Exception('ProcessHelper not set for '.get_class($this));
		if($this->output === null)
			throw new Exception('Output not set for '.get_class($this));
	}

	/**
	 * @param OutputInterface $output
	 * @return $this
	 */
	public function setOutput(OutputInterface $output) {
		$this->output = $output;
		return $this;
	}

	/**
	 * @param ProcessHelper $processHelper
	 * @return $this
	 */
	public function setProcessHelper(ProcessHelper $processHelper) {
		$this->processHelper = $processHelper;
		return $this;
	}
}