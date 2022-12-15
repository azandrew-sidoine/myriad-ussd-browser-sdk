<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface AuthorizedRequestInterface
{
    /**
     * Returns request authorization token
     * 
     * @return string 
     */
    public function requestToken();
}