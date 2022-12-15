<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestFactoryInterface;
use Psr\Http\Message\MessageInterface;

class MyriadUSSDRequestFactory implements RequestFactoryInterface
{
    /**
     * {@inheritDoc}
     * 
     * Creates an USSD request instance from 
     * Myriad request sdk definition
     * 
     * @param MessageInterface|array $message
     * 
     */
    public function createRequest($message)
    {
        Assert::assertTypeOf($message, ['array', MessageInterface::class]);
        if (is_array($message)) {
            return $this->createFromArray($message);
        }
        return $this->fromPsr7Message($message);
    }

    /**
     * Creates a USSD request from psr7 HTTP message interface
     * 
     * @param MessageInterface $message 
     * @return AuthorizedUSSDRequest 
     */
    private function fromPsr7Message(MessageInterface $message)
    {
        Assert::assertRequiredKeys($message->getHeaders(), [
            'user-entry',
            'hub-ussdcode',
        ]);
        $bodyHeader = ($header = $message->getHeader('user-entry')) ? array_pop($header) : null;
        $ussdCode = ($ussdCodeHeader = $message->getHeader('hub-ussdcode')) ? array_pop($ussdCodeHeader) : null;
        $authorizationToken = ($tokenHeader = $message->getHeader('x-application-token')) ? array_pop($tokenHeader) : null;
        $requestId = ($requestIdHeader = $message->getHeader('x-request-id')) ? array_pop($requestIdHeader) : null;
        $requestLang = ($requestLangHeader = $message->getHeader('user-language')) ? array_pop($requestLangHeader) : null;
        $request = null !== $authorizationToken ? new AuthorizedUSSDRequest(
            $bodyHeader,
            $ussdCode,
            $authorizationToken,
            $requestId,
            $requestLang
        ) : new USSDRequest(
            $bodyHeader,
            $ussdCode,
            $requestId,
            $requestLang
        );

        if ($sessionState = $message->getHeader('session-state')) {
            $request->sessionState(array_pop($sessionState));
        }

        if ($session = $message->getHeader('user-session')) {
            $request->session(array_pop($session));
        }

        if ($isoCode = $message->getHeader('user-country')) {
            $request->isoCode(array_pop($isoCode));
        }

        if ($operator = $message->getHeader('user-operator-name')) {
            $request->operator(array_pop($operator));
        }

        if ($msisdn = $message->getHeader('user-msisdn')) {
            $request->msisdn(array_pop($msisdn));
        }

        if ($alias = $message->getHeader('user-alias')) {
            $request->requestAlias(array_pop($alias));
        }

        return $request;
    }

    /**
     * Creates a USSD request from attributes array
     * 
     * @param MessageInterface $message
     * 
     * @return AuthorizedUSSDRequest|USSDRequest
     */
    private function createFromArray(array $attributes)
    {
        Assert::assertRequiredKeys($attributes, [
            'user-entry',
            'hub-ussdcode',
        ]);
        $request = isset($attributes['x-application-token']) ? new AuthorizedUSSDRequest(
            $attributes['user-entry'],
            $attributes['hub-ussdcode'],
            $attributes['x-application-token'],
            $attributes['x-request-id'],
            $attributes['user-language'],
        ) : new USSDRequest(
            $attributes['user-entry'],
            $attributes['hub-ussdcode'],
            $attributes['x-request-id'],
            $attributes['user-language'],
        );

        if (isset($attributes['session-state'])) {
            $request->sessionState($attributes['session-state']);
        }

        if (isset($attributes['user-session'])) {
            $request->session($attributes['user-session']);
        }

        if (isset($attributes['user-country'])) {
            $request->isoCode($attributes['user-country']);
        }

        if (isset($attributes['user-operator-name'])) {
            $request->operator($attributes['user-operator-name']);
        }

        if (isset($attributes['user-msisdn'])) {
            $request->msisdn($attributes['user-msisdn']);
        }

        if (isset($attributes['user-alias'])) {
            $request->requestAlias($attributes['user-alias']);
        }

        return $request;
    }
}
