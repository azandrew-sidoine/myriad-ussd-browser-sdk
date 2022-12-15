<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\USSDPageRegistryInterface;

interface USSDBrowserInterface extends USSDPageRegistryInterface
{
    /**
     * Set the callback url to which request is send to by the USSD server
     * when a client ends a given session
     * 
     * @param string $url
     * 
     * @return self 
     */
    public function setCompleteCallbackUrl(string $url);

    /**
     * Returns the url/endopoint to which callback request are send to when
     * client completes an USSD session
     * 
     * @return string 
     */
    public function getCompleteCallbackUrl();
}