<?php namespace Rancherize\Services\UnitConversionService;

/**
 * Class UnitConversionService
 * @package Rancherize\Services\UnitConversionService
 */
class UnitConversionService {

    public function convert(string $value) : int {

        preg_match( '~(\d+)([gGmM]?)~', $value, $matches );
        $convertedValue = (int)$matches[ 1 ];
        $modifier = $matches[ 2 ];
        switch ($modifier) {
            case 'g':
                /** @noinspection PhpMissingBreakStatementInspection */
            case 'G':
                $convertedValue *= 1024;

            case 'm':
                /** @noinspection PhpMissingBreakStatementInspection */
            case 'M':
                $convertedValue *= 1024;

            case 'k':
                /** @noinspection PhpMissingBreakStatementInspection */
            case 'K':
                $convertedValue *= 1024;

            default:
                break;
        }

        return $convertedValue;
    }

}