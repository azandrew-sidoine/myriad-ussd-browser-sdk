<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

trait CompletesUSSDSession
{
    /**
     * @var string
     */
    private $sessionReturnUrl;

    /**
     * {@inheritDoc}
     */
    public function setReturnURL($endpoint)
    {
        Assert::assertTypeOf($endpoint, ['string', \Stringable::class], __METHOD__);
        $this->sessionReturnUrl = $endpoint;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getReturnURL()
    {
        return $this->sessionReturnUrl;
    }
}
