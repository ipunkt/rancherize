<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\CustomFiles;


trait CustomFilesTrait {

	/**
	 * @var CustomFilesMaker
	 */
	protected $customFilesMaker = null;

	public function getCustomFilesMaker() {
		if( $this->customFilesMaker === null )
			$this->customFilesMaker = container('custom-files-maker');

		return $this->customFilesMaker;
	}
}