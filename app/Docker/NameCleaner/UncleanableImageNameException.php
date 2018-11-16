<?php namespace Rancherize\Docker\NameCleaner;

/**
 * Class UncleanableImageNameException
 * @package Rancherize\Docker\NameCleaner
 */
class UncleanableImageNameException extends \RuntimeException
{

    /**
     * UncleanableImageNameException constructor.
     * @param string $name
     * @param int $code
     * @param \Exception|null $e
     */
    public function __construct(string $name, int $code = 0, \Exception $e = null)
    {
        parent::__construct("Uncleanable image name: $name", $code, $e);
    }

}