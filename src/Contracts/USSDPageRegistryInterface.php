<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

use InvalidArgumentException;
use UnexpectedValueException;

interface USSDPageRegistryInterface
{
    /**
     * Add a new page to the ussd browser page stack
     * 
     * @param array|PageInterface $instance 
     * @return self 
     * @throws InvalidArgumentException 
     * @throws UnexpectedValueException 
     */
    public function addPage($instance);

    /**
     * Check if the registry has a given page
     * 
     * @param int|string|PageInterface $page
     * 
     * @return bool 
     */
    public function hasPage($page);

    /**
     * Returns the page instance matching the provided $id value
     * 
     * @param string|int $id
     * 
     * @return PageInterface|null 
     */
    public function getPage($id);

    /**
     * Remove an USSD page from the USSD page registry
     * 
     * @param int|string|PageInterface $page
     * 
     * @return void 
     */
    public function removePage($page);

    /**
     * Returns the list of pages defines in the USSD browser
     * 
     * @return PageInterface[] 
     */
    public function allPages();
}
