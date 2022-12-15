<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface StatefulRequestInterface
{
    /**
     * Returns a request state value based user interaction
     * 
     * @return string 
     */
    public function sessionState();

    /**
     * Returns the session id of the request instance
     * 
     * @return int|string 
     */
    public function session();
}