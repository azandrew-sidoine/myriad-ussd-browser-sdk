<?php

use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestControllerInterface;

interface ProvidesRequestController
{
    /**
     * Set the request controller thatn for the current instance
     * 
     * @param RequestControllerInterface $controller
     * 
     * @return self|mixed
     */
    public function addController(RequestControllerInterface $controller);

    /**
     * Returns the controller that the current instance provides
     * 
     * @return RequestControllerInterface 
     */
    public function getController();
}