<?php


namespace Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\Traits;


use Rancherize\Blueprint\Infrastructure\Service\Maker\PhpFpm\PostLimit;

trait PostLimitTrait {

	/**
	 * @var string
	 */
	private $postLimit = PostLimit::DEFAULT_POST_LIMIT;

	/**
	 * @return $this
	 */
	public function setPostLimit( $limit ) {
		$this->postLimit = $limit;
		return $this;
	}
}