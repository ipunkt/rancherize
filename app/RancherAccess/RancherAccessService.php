<?php
/**
 * rancherize
 *
 * @author bastian
 * @since 24.08.17
 */

namespace Rancherize\RancherAccess;


/**
 * Class RancherAccessConfigService
 * @package Rancherize\Services
 *
 * Provides RancherAccounts from the configuration
 */
interface RancherAccessService
{
    /**
     * @return string[]
     */
    public function availableAccounts();

    /**
     * @param string $name
     * @return ArrayRancherAccount
     */
    public function getAccount(string $name) : ArrayRancherAccount;

	/**
	 * @param Configuration $config
	 */
    public function parse(\Rancherize\Configuration\Configuration $config);
}