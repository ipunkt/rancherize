<?php namespace Rancherize\Configuration\Exceptions;

/**
 * Class GlobalConfigurationNotFoundException
 * @package Rancherize\Configuration\Exceptions
 *
 * Thrown when the global configuration in ${HOME}/.rancherize is empty when trying to load it
 */
class GlobalConfigurationNotFoundException extends FileNotFoundException  {

}