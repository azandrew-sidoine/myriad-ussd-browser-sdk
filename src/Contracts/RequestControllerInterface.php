<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface RequestControllerInterface
{
    /**
     * Provides implementation for handling a page request
     * 
     * @param object|array $rerquest
     * 
     * @return mixed 
     */
    public function onRequest($request);

}