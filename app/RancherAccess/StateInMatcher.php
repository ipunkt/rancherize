<?php namespace Rancherize\RancherAccess;

/**
 * Class SingleStateMatcher
 * @package Rancherize\RancherAccess
 */
class StateInMatcher implements StateMatcher
{
    /**
     * @var array
     */
    private $expectedStates = [];

    /**
     * SingleStateMatcher constructor.
     * @param string $expectedState
     */
    public function __construct(array $expectedStates)
    {
        foreach ($expectedStates as $expectedState) {
            $this->expectedStates[] = strtolower($expectedState);
        }
    }

    /**
     * @param $service
     * @return bool
     */
    public function match($service)
    {
        return in_array(strtolower($service['state']), $this->expectedStates);
    }
}