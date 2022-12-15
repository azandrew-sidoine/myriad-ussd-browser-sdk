<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\AuthorizedRequestInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\StatefulRequestInterface;

class AuthorizedUSSDRequest implements
    RequestInterface,
    StatefulRequestInterface,
    AuthorizedRequestInterface
{

    use USSDRequestTrait;

    /**
     * Request authorization token
     * 
     * @var string
     */
    private $authorizationToken;

    /**
     * Creates an instance of USSD request
     * 
     * @param string $body 
     * @param string $ussdCode
     * @param string $token
     * @param string|null $id
     * @param string|null $lang
     * 
     * @throws InvalidArgumentException 
     */
    public function __construct(
        $body,
        $ussdCode,
        $token,
        $id = null,
        string $lang = 'en'
    ) {
        Assert::assertTypeOf($body, ['string']);
        Assert::assertTypeOf($ussdCode, ['string']);
        Assert::assertTypeOf($token, ['string']);
        Assert::assertTypeOf($id, ['int', 'string']);
        $this->body = $body;
        $this->ussdCode = $ussdCode;
        $this->authorizationToken = $token;
        $this->lang =  $lang ?? $lang;
        $this->id = $id;
    }

    public function requestToken()
    {
        return $this->authorizationToken;
    }
}
