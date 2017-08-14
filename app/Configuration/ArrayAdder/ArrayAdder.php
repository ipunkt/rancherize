<?php namespace Rancherize\Configuration\ArrayAdder;

use Closure;
use Rancherize\Configuration\Configuration;

/**
 * Class ArrayAdder
 * @package Rancherize\Configuration\ArrayAdder
 *
 * Helper to add all values from an array
 * This circumvents that $config->get('array'); with fallback configuration will not 'merge' the arrays
 * Use $arrayAdder->addAll([$environmentConfig, $projectConfig], 'config-entry', function($name, $value) { do_something(); })
 */
class ArrayAdder {
	/**
	 * @param Configuration[] $configs
	 * @param string $label
	 * @param Closure $closure
	 */
	public function addAll(array $configs, string $label, Closure $closure) {
		foreach($configs as $c) {
			if(!$c->has($label))
				continue;

			foreach ($c->get($label) as $name => $value)
				$closure($name, $value);
		}
	}


}