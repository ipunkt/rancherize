<?php namespace Rancherize\Push\ModeFactory;

use Rancherize\Push\Modes\PushMode;

/**
 * Class ModeAndParser
 * @package Rancherize\Push\PushModeFactory
 */
class ModeAndParser {

	/**
	 * @var PushMode
	 */
	protected $pushMode;
	/**
	 * @var PushModeParser
	 */
	private $pushModeParser;

	/**
	 * ModeAndParser constructor.
	 * @param PushModeParser $pushModeParser
	 * @param PushMode $pushMode
	 */
	public function __construct( PushModeParser $pushModeParser = null, PushMode $pushMode = null) {
		$this->pushModeParser = $pushModeParser;
		$this->pushMode = $pushMode;
	}

	/**
	 * @return PushModeParser
	 */
	public function getModeParser(): PushModeParser {
		return $this->pushModeParser;
	}

	/**
	 * @param PushModeParser $modeParser
	 */
	public function setModeParser( PushModeParser $modeParser ) {
		$this->modeParser = $modeParser;
	}

	/**
	 * @return PushMode
	 */
	public function getPushMode(): PushMode {
		return $this->pushMode;
	}

	/**
	 * @param PushMode $pushMode
	 */
	public function setPushMode( PushMode $pushMode ) {
		$this->pushMode = $pushMode;
	}

}