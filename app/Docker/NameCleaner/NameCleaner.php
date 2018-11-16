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

        $characterRegex = '~[^a-zA-Z0-9]~';

        $cleanedName = $name;
        while (preg_match($characterRegex, $cleanedName)) {
            $cleanedName = preg_replace($characterRegex, '', $cleanedName);
        }
        $shortenedName = substr($cleanedName, -30);

        return $shortenedName;
    }

}