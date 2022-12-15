<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\StatefulRequestInterface;
use InvalidArgumentException;

class USSDRequest implements
    RequestInterface,
    StatefulRequestInterface
{
    use USSDRequestTrait;

    /**
     * Creates an instance of USSD request
     * 
     * @param mixed $body 
     * @param mixed $ussdCode 
     * @param string|null $id
     * @param string|null $lang
     * 
     * @throws InvalidArgumentException 
     */
    public function __construct($body, $ussdCode, $id = null, string $lang = 'en')
    {
        Assert::assertTypeOf($body, ['string']);
        Assert::assertTypeOf($ussdCode, ['string']);
        Assert::assertTypeOf($id, ['int', 'string']);
        $this->body = $body;
        $this->ussdCode = $ussdCode;
        $this->lang =  $lang ?? $lang;
        $this->id = $id;
    }
}
