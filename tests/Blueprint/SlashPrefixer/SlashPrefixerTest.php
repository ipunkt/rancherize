<?php namespace RancherizeTest\Blueprint\SlashPrefixer;

use Rancherize\Blueprint\Services\Directory\Service\SlashPrefixer;
use RancherizeTest\TestCase;

/**
 * Class SlashPrefixerTest
 * @package RancherizeTest\Blueprint\SlashPrefixer
 */
class SlashPrefixerTest extends TestCase {

	/**
	 * @var SlashPrefixer
	 */
	protected $slashPrefixer;

	public function setUp(  ) {
		$this->slashPrefixer = new SlashPrefixer();
	}

	/**
	 * @test
	 */
	public function testEmpty(  ) {
		$prefixed = $this->slashPrefixer->prefix('');

		$this->assertEquals('', $prefixed);
	}

	/**
	 * @test
	 */
	public function testNoSlash(  ) {
		$prefixed = $this->slashPrefixer->prefix('test');

		$this->assertEquals('/test', $prefixed);
	}

	/**
	 * @test
	 */
	public function testSlash(  ) {
		$prefixed = $this->slashPrefixer->prefix('/cookies');

		$this->assertEquals('/cookies', $prefixed);
	}
}