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

        $firstCharacterRegex = '[^a-zA-Z0-9]';
        $characterRegex = '~[^a-zA-Z0-9_.-]~';

        if (preg_match($firstCharacterRegex, $name[0])) {
            $name = substr($name, 1);
        }

        $cleanedName = preg_replace($characterRegex, '', $name);

        return $cleanedName;
    }

}