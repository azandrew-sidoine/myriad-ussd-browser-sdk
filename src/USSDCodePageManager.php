<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\USSDBrowserInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\USSDCodePageManager as ContractsUSSDCodePageManager;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\USSDPageIdFactory;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\ProvidesRequestController;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestControllerInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestInterface;
use RuntimeException;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\LastUSSDPageFactoryInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestFactoryInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\USSDPageRegistryInterface;

class USSDCodePageManager implements ContractsUSSDCodePageManager, RequestFactoryInterface
{
    /**
     * USSD page browser registry instance
     * 
     * @var USSDPageRegistryInterface
     */
    private $registry;

    /**
     * @var USSDPageIdFactory|callable|null
     */
    private $ussdPageIdFactory;

    /**
     * Fallback request controller that is invoked when
     * page does not provides a request controller
     * 
     * @var RequestControllerInterface
     */
    private $defaultRequestController;

    /**
     * @var string
     */
    private $lastPageRequestUrl;

    /**
     * @var LastUSSDPageFactoryInterface|callable
     */
    private $lastPageFactory;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * Creates an instance of USSD code Page Manager
     * 
     * @param USSDPageRegistryInterface $registry 
     * @param USSDPageIdFactory|callable|null $ussdPageIdFactory 
     * @param RequestControllerInterface|null $defaultController 
     * @return void 
     */
    public function __construct(
        USSDPageRegistryInterface $registry,
        RequestFactoryInterface $requestFactory,
        callable $ussdPageIdFactory = null,
        RequestControllerInterface $defaultController = null,
        callable $lastPageFactory = null,
        string $lastPageRequestUrl = null
    ) {
        $this->registry = $registry;
        $this->requestFactory = $requestFactory;
        if (null !== $lastPageRequestUrl) {
            $this->lastPageRequestUrl = $lastPageRequestUrl;
        }
        $this->setPageIdFactory($ussdPageIdFactory);
        $this->setCompleteSessionPageFactory($lastPageFactory);
        if (null !== $defaultController) {
            $this->useRequestController($defaultController);
        }
    }

    public function createRequest($message)
    {
        return $this->requestFactory->createRequest($message);
    }

    public function addUSSDCodePage($instance, string $ussdcode)
    {
        /**
         * @var PageInterface
         */
        $page = is_array($instance) ? (isset($instance['callback']) ?
            RequestControllerPage::fromArray($instance) :
            Page::fromArray($instance)) : $instance;
        Assert::assertTypeOf($page, [PageInterface::class]);
        $id = ($this->ussdPageIdFactory)($ussdcode);
        Assert::assertTypeOf($page, ['string', 'int']);
        $this->registry->addPage($page->withPageId($id));
        return $this;
    }

    public function getUSSDPageRegistry()
    {
        return $this->registry;
    }

    public function ussdCodeToPage(string $ussdcode)
    {
        $id = ($this->ussdPageIdFactory)($ussdcode);
        Assert::assertTypeOf($id, ['string', 'int']);
        if (!$this->registry->hasPage($id)) {
            throw new RuntimeException('No Page found for ussd code ' . $ussdcode);
        }
        return $this->registry->getPage($id);
    }

    /**
     * Provides a default controller to use if no page provides controller
     * 
     * @param RequestControllerInterface $controller 
     * 
     * @return static 
     */
    public function useRequestController(RequestControllerInterface $controller)
    {
        $this->defaultRequestController = $controller;
        return $this;
    }

    public function handlePageRequest($id, RequestInterface $request)
    {
        if (!$this->registry->hasPage($id)) {
            if (null === $this->defaultRequestController) {
                throw new RuntimeException('No request controller found');
            }
            return $this->defaultRequestController->onRequest($request);
        }
        $page = $this->registry->getPage($id);
        if ($page instanceof RequestControllerInterface) {
            return $this->onPageResponse($page->onRequest($request));
        }
        if ($page instanceof ProvidesRequestController) {
            return $this->onPageResponse($page->getController()->onRequest($request));
        }
        if (null !== $this->defaultRequestController) {
            return $this->onPageResponse($this->defaultRequestController->onRequest($request));
        }
        throw new RuntimeException('Page ' . $page->id() . ' is not a request handler, nor provides a request controller instance');
    }

    private function setPageIdFactory(callable $ussdPageIdFactory = null)
    {
        if (null === $ussdPageIdFactory) {
            $ussdPageIdFactory = new class implements USSDPageIdFactory
            {
                public function __invoke(string $ussdcode)
                {
                    // We simply create an md5 hash of the ussd code to create
                    // page id variable
                    return md5($ussdcode);
                }
            };
        }
        $this->ussdPageIdFactory = $ussdPageIdFactory;
    }

    private function setCompleteSessionPageFactory(callable $factory)
    {
        $factory = $factory ?? new class(
            $this->registry instanceof USSDBrowserInterface ?
                $this->registry->getCompleteCallbackUrl() ?? $this->lastPageRequestUrl :
                $this->lastPageRequestUrl
        ) implements LastUSSDPageFactoryInterface
        {
            /**
             * @var string
             */
            private $url;

            public function __construct(string $url = null)
            {
                $this->url = $url;
            }

            public function __invoke(): PageInterface
            {
                $page = new Page(md5('session_ends'), 'Session Completed', 'Thanks for using our service');
                if (null !== $this->url) {
                    $page->setReturnURL($this->url);
                }
                return $page;
            }
        };
        $this->lastPageFactory = $factory;
    }

    private function onPageResponse(PageInterface $page = null)
    {
        if (null === $page) {
            return $this->createSessionLastPage();
        }
        return $page;
    }

    private function createSessionLastPage()
    {
        return ($this->lastPageFactory)();
    }
}
