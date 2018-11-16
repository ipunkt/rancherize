<?php namespace Rancherize\Docker\NameCleaner;

/**
 * Class NameCleaner
 * @package Rancherize\Docker\NameCleaner
 *
 * Clean a name to work as container name
 */
class NameCleaner
{

    /**
     * @param $name
     */
    public function cleanName($name)
    {
        if (!is_string($name) || empty($name)) {
            throw new UncleanableImageNameException($name);
        }

        $characterRegex = '[^a-zA-Z0-9]';

        $cleanedName = preg_replace($characterRegex, '', $name);
        $shortenedName = substr($cleanedName, 0, min(strlen($cleanedName), 30));

        return $shortenedName;
    }

}