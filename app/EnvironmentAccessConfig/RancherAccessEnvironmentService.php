<?php namespace Rancherize\EnvironmentAccessConfig;

use Rancherize\RancherAccess\ArrayRancherAccount;
use Rancherize\RancherAccess\RancherAccessService;

/**
 * Class RancherAccessConfigService
 * @package Rancherize\Services
 *
 * Provides RancherAccounts from the configuration
 */
class RancherAccessEnvironmentService implements RancherAccessService
{

    /**
     * @var array
     */
    private $account = [];

    public function __construct()
    {
        $this->account = [
            'url' => getenv('RANCHER_URL'),
            'key' => getenv('RANCHER_KEY'),
            'secret' => getenv('RANCHER_SECRET')
        ];
    }

    /**
     * @return string[]
     */
    public function availableAccounts()
    {
        return ['default'];
    }

    /**
     * @param string $name
     * @return ArrayRancherAccount
     */
    public function getAccount(string $name): ArrayRancherAccount
    {

        $capitalName = $this->makeCapitalOnlyName($name);
        if ($this->nameExistsInEnvironment($capitalName)) {
            return $this->makeAccount($capitalName);
        }

        $environmentName = $this->makeCapitalAndUnderscores($name);
        if ($this->nameExistsInEnvironment($environmentName)) {
            return $this->makeAccount($environmentName);
        }

        return new ArrayRancherAccount($this->account);
    }

    /**
     * @param $name
     * @return string
     */
    protected function makeCapitalOnlyName($name)
    {
        return strtoupper($name);
    }

    /**
     * @param $name
     * @return string
     */
    protected function makeCapitalAndUnderscores($name)
    {

        $capitalName = strtoupper($name);

        $capitalNameWithUnderscores = str_replace('-', '_', $capitalName);

        return $capitalNameWithUnderscores;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function nameExistsInEnvironment($name)
    {
        $url = getenv('RANCHER_' . $name . '_URL');

        $urlIsSet = !empty($url);

        if ($urlIsSet) {
            return true;
        }

        return false;
    }

    /**
     * @param $name
     * @return ArrayRancherAccount
     */
    protected function makeAccount($name)
    {
        return new ArrayRancherAccount([
            'url' => getenv('RANCHER_' . $name . '_URL'),
            'key' => getenv('RANCHER_' . $name . '_KEY'),
            'secret' => getenv('RANCHER_' . $name . '_SECRET')
        ]);
    }
}