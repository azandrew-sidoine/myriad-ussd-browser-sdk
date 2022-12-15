<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface USSDPageIdFactory
{
    /**
     * Creates a USSD Page id from a ussd code
     * 
     * @param string $ussdcode
     * 
     * @return string
     */
    public function __invoke(string $ussdcode);
}