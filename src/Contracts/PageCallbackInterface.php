<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface PageCallbackInterface
{
    /**
     * Implementation are required to provided the algorithm
     * to handle response object from the ussd platform service
     * 
     * @param mixed $args 
     * @return mixed 
     */
    public function __invoke(...$args);
}
