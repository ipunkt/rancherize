<?php namespace Rancherize\Validation\ServiceNameValidation;

use Rancherize\Blueprint\Validation\Exceptions\ValidationFailedException;
use Rancherize\Events\ValidatingEvent;

/**
 * Class EventHandler
 * @package Rancherize\Validation\ServiceNameValidation
 */
class EventHandler
{

    public function validate( ValidatingEvent $e ) {

        $configuration = $e->getConfiguration();
        if( $configuration->has('service-name') )
            throw new ValidationFailedException([ 'service-name' => [
                'service-name is not set. This sets the name of the service inside Rancher'
            ]
            ]);


        $name = $configuration->get('service-name');

        $matches = [];
        if( !preg_match_all($name, '~([^a-zA-Z0-9\._\-]+)~g', $name, $matches) )
            return;

        $errors = [

        ];
        foreach($matches[0] as $match)
            $errors[] = 'Invalid characters '.implode(', ', "'$match'");

        throw new ValidationFailedException([ 'service-name' => $errors ]);
    }
}