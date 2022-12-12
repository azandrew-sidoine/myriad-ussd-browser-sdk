<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface LinkInterface extends Arrayable
{
    /**
     * Setter and getter for link id property 
     * 
     * @param int|null $value
     * 
     * @return string 
     */
    public function id(int $value = null);

    /**
     * Setter and getter for link content property 
     * 
     * @param string|null $value
     * 
     * @return string 
     */
    public function content($value = null);

    /**
     * Setter and getter of the method used when sending inputs
     * 
     * @param string|null $value
     * 
     * @return string 
     */
    public function method(string $value = null);

    /**
     * Setter and getter of the url to which the link is submitted to
     * 
     * @param string|null $value
     * 
     * @return string 
     */
    public function to(string $value = null);
}
