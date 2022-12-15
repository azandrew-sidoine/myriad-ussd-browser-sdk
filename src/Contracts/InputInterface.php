<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface InputInterface extends Arrayable
{
    /**
     * Setter and getter of the method used when sending inputs
     * 
     * @param string|null $value
     * 
     * @return string 
     */
    public function method(string $value = null);

    /**
     * Setter and getter of the url/endpoint to which input value
     * is submitteb by default
     * 
     * @param string|null $value
     * 
     * @return string 
     */
    public function endpoint(string $value = null);

    /**
     * Setter and getter for the input type attribute
     * 
     * @param string|null $value
     *  
     * @return string 
     */
    public function type(string $value = null);

    /**
     * Setter and getter for the input name attribute
     * 
     * @param string|null $value 
     * 
     * @return string 
     */
    public function name(string $value = null);

    /**
     * Mark the input as password input. Input from user
     * are treated as secret value
     * 
     * @return self 
     */
    public function masked();

    /**
     * Provides a getter and setter contract to input instances
     * 
     * @param int|null $value
     * 
     * @return int
     */
    public function constraints(int $max = null, int $min = null);
}