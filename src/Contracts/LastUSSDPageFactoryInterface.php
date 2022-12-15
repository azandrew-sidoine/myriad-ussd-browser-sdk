<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface LastUSSDPageFactoryInterface
{
    /**
     * Creates a page instance that completes the client
     * ussd session
     * 
     * @return PageInterface 
     */
    public function __invoke(): PageInterface;
}