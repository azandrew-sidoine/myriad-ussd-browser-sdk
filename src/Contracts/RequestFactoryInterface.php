<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface RequestFactoryInterface
{

    /**
     * Creates an USSD request instance
     * 
     * @param mixed $message
     * 
     * @return RequestInterface 
     */
    public function createRequest($message);
}
