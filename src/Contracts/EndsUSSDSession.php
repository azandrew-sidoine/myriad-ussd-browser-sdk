<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface EndsUSSDSession
{
    /**
     * Implementation should provides a setter for enpoint
     * which is called or invoked by the USSD Server to signal
     * session ends event
     * 
     * @param \Stringable|string $uri
     * 
     * @return static&mixed
     */
    public function setReturnURL($endpoint);

    /**
     * Implementation should provides a getter for enpoint
     * which is called or invoked by the USSD Server to signal
     * session ends event
     * 
     * @return string 
     */
    public function getReturnURL();
}