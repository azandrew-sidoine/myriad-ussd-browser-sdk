<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface RequestControllerInterface
{
    /**
     * Provides implementation for handling a page request
     * 
     * @param RequestInterface $request
     * 
     * @return PageInterface|null 
     */
    public function onRequest(RequestInterface $request);
}
