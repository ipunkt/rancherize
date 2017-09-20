<?php


namespace Rancherize\Blueprint\ProjectName;


use Rancherize\Blueprint\ProjectName\ProjectNameService\ProjectNameService;

trait ProjectNameTrait {
	/**
	 * @var ProjectNameService
	 */
	protected $projectNameService;

	/**
	 * @param ProjectNameService $projectNameService
	 */
	public function setProjectNameService( ProjectNameService $projectNameService ) {
		$this->projectNameService = $projectNameService;
	}
}