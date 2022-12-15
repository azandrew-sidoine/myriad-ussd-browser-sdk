<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

use RuntimeException;

interface USSDCodePageManager
{
    /**
     * Resolve Page instance from USSD code
     * 
     * @param string $ussdcode
     * 
     * @return PageInterface 
     */
    public function ussdCodeToPage(string $ussdcode);

    /**
     * Returns the USSD browser instance that contains
     * the list of USSD pages that can be serv to the 
     * USSD server
     * 
     * @return USSDPageRegistryInterface 
     */
    public function getUSSDPageRegistry();

    /**
     * Add USSD page for a given USSD code
     * 
     * @param array|PageInterface $instance 
     * @param string $ussdcode 
     * @return mixed 
     */
    public function addUSSDCodePage($instance, string $ussdcode);

    /**
     * Provides implementation to respond to client request for a given page
     * 
     * @param int|string $id 
     * @param RequestInterface $request
     * 
     * @return PageInterface
     * 
     * @throws RuntimeException 
     */
    public function handlePageRequest($id, RequestInterface $request);
}
