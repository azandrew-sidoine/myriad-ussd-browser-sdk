<?php

use Drewlabs\MyriadUssdBrowserSdk\Assert;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestControllerInterface;
use Drewlabs\MyriadUssdBrowserSdk\Page;
use Drewlabs\MyriadUssdBrowserSdk\RequestControllerPage;

class USSDBrowser
{
    /**
     * List of ussd browser pages
     * 
     * @var PageInterface[]
     */
    private $pages = [];

    /**
     * Fallback request controller that is invoked when
     * page does not provides a request controller
     * 
     * @var RequestControllerInterface
     */
    private $defaultRequestController;


    /**
     * Url which is called when client end a ussd session
     * 
     * @var string
     */
    private $callbackUrl;

    public function __construct(
        array $pages = [],
        string $callbackUrl = null,
        RequestControllerInterface $defaultController = null
    ) {

        if (!empty($pages)) {
            $this->addPages($pages);
        }

        if (null !== $callbackUrl) {
            $this->registerCompleteCallbackUrl($callbackUrl);
        }

        if (null !== $defaultController) {
            $this->useRequestController($defaultController);
        }
    }


    /**
     * Set the callback url to which request is send to by the USSD server
     * when a client ends a given session
     * 
     * @param string $url
     * 
     * @return self 
     */
    public function registerCompleteCallbackUrl(string $url)
    {
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Expect parameter of ' . __METHOD__ . ' to be a RFC 2396 - Uniform Resource Identifiers (URI) compliant');
        }
        $this->callbackUrl = $url;
        return $this;
    }

    /**
     * Add a new page to the ussd browser page stack
     * 
     * @param array|PageInterface $page 
     * @return self 
     * @throws InvalidArgumentException 
     * @throws UnexpectedValueException 
     */
    public function addPage($page)
    {
        Assert::assertTypeOf($page, ['array', PageInterface::class]);
        if (is_array($page)) {
            $id = $page['id'] ?? array_map(function ($char) {
                return ord($char);
            }, str_split((string)uniqid(time())));
            $page['id'] = $id;
        } else {
            $id = $page->id();
        }
        // There is no need to add a having the same id as an existing page
        if ($page = $this->getPage($id)) {
            return $this;
        }
        $page_ = is_array($page) ? (isset($page['callback']) ? RequestControllerPage::fromArray($page) : Page::fromArray($page)) : $page;
        $this->pages[] = $page_;
        return $this;
    }

    /**
     * Add a list of pages to the pages prorperty
     * 
     * @param array|PageInterface[] $pages 
     * 
     * @return self 
     */
    public function addPages(array $pages)
    {
        foreach ($pages as $page) {
            $this->addPage($page);
        }
        return $this;
    }


    /**
     * Returns the page instance matching the provided $id value
     * 
     * @param string|int $id
     * 
     * @return PageInterface|null 
     */
    public function getPage($id)
    {
        /**
         * @var PageInterface $page
         */
        foreach ($this->pages ?? [] as $page) {
            if ($page->id() === $id) {
                return $page;
            }
        }
        return null;
    }

    /**
     * Returns the list of pages defines in the USSD browser
     * 
     * @return PageInterface[] 
     */
    public function allPages()
    {
        return $this->pages ?? [];
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

    /**
     * 
     * @param mixed $id 
     * @param object $request 
     * @return mixed 
     * @throws RuntimeException 
     */
    public function handlePageRequest($id, object $request)
    {
        if (null === ($page = $this->getPage($id))) {
            if (null === $this->defaultRequestController) {
                throw new RuntimeException('No request controller found');
            }
            return $this->defaultRequestController->onRequest($request);
        }
        if ($page instanceof RequestControllerInterface) {
            return $page->onRequest($request);
        }

        if ($page instanceof ProvidesRequestController) {
            return $page->getController()->onRequest($request);
        }

        if (null !== $this->defaultRequestController) {
            return $this->defaultRequestController->onRequest($request);
        }
        throw new RuntimeException('Page ' . $page->id() . ' is not a request handler, nor provides a request controller instance');
    }
}
