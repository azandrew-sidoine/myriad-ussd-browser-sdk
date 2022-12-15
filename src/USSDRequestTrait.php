<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

trait USSDRequestTrait
{
    /**
     * Request id
     * 
     * @var string|int
     */
    private $id;

    /**
     * Actual request body
     * 
     * @var string
     */
    private $body;

    /**
     * Request ussd code
     * 
     * @var string
     */
    private $ussdCode;

    /**
     * Request country iso3366 code
     * 
     * @var string
     */
    private $isoCode;

    /**
     * Request lang
     * 
     * @var string
     */
    private $lang;

    /**
     * Request operator id
     * 
     * @var string
     */
    private $operator;

    /**
     * Request session id
     * 
     * @var string
     */
    private $session;

    /**
     * Request session state
     * 
     * @var string
     */
    private $sessionState;

    /**
     * 
     * @var string
     */
    private $msisdn;

    /**
     * 
     * @var string
     */
    private $alias;

    public function id()
    {
        return $this->id;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getRequestLang()
    {
        return $this->lang;
    }

    public function getUSSDCode()
    {
        return $this->ussdCode;
    }

    public function isoCode(string $value = null)
    {
        if (null !== $value) {
            $this->isoCode = $value;
        }
        return $this->isoCode;
    }

    public function operator(string $value = null)
    {
        if (null !== $value) {
            $this->operator = $value;
        }
        return $this->operator;
    }

    public function sessionState(string $value = null)
    {
        if (null !== $value) {
            $this->sessionState = $value;
        }
        return $this->sessionState;
    }

    public function session(string $value = null)
    {
        if (null !== $value) {
            $this->session = $value;
        }
        return $this->session;
    }

    public function msisdn(string $value = null)
    {
        if (null !== $value) {
            $this->msisdn = $value;
        }
        return $this->msisdn;
    }

    public function requestAlias(string $value = null)
    {
        if (null !== $value) {
            $this->alias = $value;
        }
        return $this->alias;
    }
}
