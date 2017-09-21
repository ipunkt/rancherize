<?php namespace Rancherize\Blueprint\Services\Directory\Service;

/**
 * Class SlashPrefixer
 * @package Rancherize\Blueprint\Services\Directory\Service
 */
class SlashPrefixer {

	/**
	 * @param $string
	 * @return string
	 */
	public function prefix( $directory ) {

		if( strlen($directory) <= 0 )
			return $directory;

		if($directory[0] === '/')
			return $directory;

		return '/'.$directory;
	}

}