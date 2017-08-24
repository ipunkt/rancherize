<?php

namespace Rancherize\Configuration\Loader;
use Rancherize\Configuration\Configurable;

/**
 * Class NullLoader
 *
 *
 *
 * @package Rancherize\Configuration\Loader
 */
class NullLoader implements Loader
{

    /**
     * @param Configurable $configurable
     * @param string $path
     */
    function load(Configurable $configurable, string $path)
    {
        // do nothing
    }

    /**
     * @param string|null $prefix
     * @return $this
     */
    function setPrefix(string $prefix = null): Loader
    {
        $this->prefix = $prefix;
        return $this;
    }
}