<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface RequestInterface
{
    /**
     * Returns the request id property value
     * 
     * @return mixed 
     */
    public function getBody();

    /**
     * Returns the request language property value
     * 
     * @return mixed 
     */
    public function getRequestLang();

    /**
     * Returns the request ussd code
     * 
     * @return string 
     */
    public function getUSSDCode();
}