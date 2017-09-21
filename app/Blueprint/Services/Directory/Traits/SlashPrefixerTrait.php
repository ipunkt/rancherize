<?php namespace Rancherize\Blueprint\Services\Directory\Traits;

use Rancherize\Blueprint\Services\Directory\Service\SlashPrefixer;

trait SlashPrefixerTrait {

	/**
	 * @var SlashPrefixer
	 */
	protected $slashPrefixer;

	/**
	 * @param SlashPrefixer $slashPrefixer
	 */
	public function setSlashPrefixer( SlashPrefixer $slashPrefixer ) {
		$this->slashPrefixer = $slashPrefixer;
	}

}