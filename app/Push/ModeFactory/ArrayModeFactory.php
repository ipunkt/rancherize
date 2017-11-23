<?php namespace Rancherize\Push\ModeFactory;

use Rancherize\Configuration\Configuration;
use Rancherize\Push\Modes\PushMode;

/**
 * Class ArrayModeFactory
 * @package Rancherize\Push\ModeFactory
 */
class ArrayModeFactory implements ModeFactory {

	/**
	 * @var ModeAndParser[]
	 */
	protected $modes = [];

	/**
	 * @param PushModeParser $pushModeParser
	 * @param PushMode $mode
	 */
	public function register( PushModeParser $pushModeParser, PushMode $mode ) {
		$this->modes[] = new ModeAndParser($pushModeParser, $mode);
	}

	/**
	 * @param Configuration $configuration
	 * @return PushMode
	 */
	public function make( Configuration $configuration ) {

		foreach($this->modes as $modeAndParser) {
			if( $modeAndParser->getModeParser()->isMode($configuration) )
				return $modeAndParser->getPushMode();

		}

		throw new UnkownModeException();
	}
}