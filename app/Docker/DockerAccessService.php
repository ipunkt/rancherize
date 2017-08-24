<?php
/**
 * rancherize
 *
 * @author bastian
 * @since 24.08.17
 */

namespace Rancherize\Docker;
use Rancherize\Configuration\Configuration;


/**
 * Class DockerAccessConfigService
 * @package Rancherize\Docker
 *
 * Reads the DockerAccounts from the configuration
 */
interface DockerAccessService
{
    /**
     * @return array
     */
    public function availableAccounts();

    /**
     * @param string $name
     * @return DockerAccount
     */
    public function getAccount(string $name): DockerAccount;

    /**
     * @param $configuration
     */
    public function parse(Configuration $configuration);
}