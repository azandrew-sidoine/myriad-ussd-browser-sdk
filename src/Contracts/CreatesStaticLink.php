<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface CreatesStaticLink
{
    /**
     * Makes the link static meaning it's shown on all page section
     * 
     * @return LinkInterface 
     */
    public function asStatic();
}