<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Assert;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestControllerInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\USSDBrowserInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\USSDPageRegistryInterface;
use Drewlabs\MyriadUssdBrowserSdk\Page;
use Drewlabs\MyriadUssdBrowserSdk\RequestControllerPage;
use InvalidArgumentException;

class USSDBrowser implements USSDPageRegistryInterface, USSDBrowserInterface
{
    /**
     * List of ussd browser pages
     * 
     * @var PageInterface[]
     */
    private $pages = [];

    /**
     * List of USSD pages ids
     * 
     * @var array
     */
    private $pageids = [];


    /**
     * url which is called when client end a ussd session
     * 
     * @var string
     */
    private $completeCallbackUrl;

    /**
     * Creates an instance of USSD Browser
     * 
     * @param array $pages 
     * @param string|null $completeCallbackUrl 
     * @param RequestControllerInterface|null $defaultController 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws UnexpectedValueException 
     */
    public function __construct(array $pages = [], string $completeCallbackUrl = null)
    {
        if (!empty($pages)) {
            $this->addPages($pages);
        }
        if (null !== $completeCallbackUrl) {
            $this->setCompleteCallbackUrl($completeCallbackUrl);
        }
    }

    public function hasPage($page)
    {
        $id = $page instanceof PageInterface ? $page->id() : $page;
        return null !== ($this->pageids[$id] ?? null);
    }

    public function addPage($instance)
    {
        $page = is_array($instance) ? (isset($instance['callback']) ?
            RequestControllerPage::fromArray($instance) :
            Page::fromArray($instance)) : $instance;
        Assert::assertTypeOf($page, [PageInterface::class]);
        // There is no need to add a having the same id as an existing page
        if ($page === $this->getPage($page->id())) {
            return $this;
        }
        // We add the page to the internal list of pages
        // We also add the pageid to the internal list of pages for fast search
        $this->pages[] = $page;
        // Note: The index of the current page is the index of 
        // element in the internal list of pages
        $this->pageids[$page->id()] = count($this->pages) - 1;
        return $this;
    }

    public function getPage($id)
    {
        $index = $this->pageids[$id] ?? null;
        if (null === $index) {
            return null;
        }
        return $this->pages[$index];
    }

    public function allPages()
    {
        return $this->pages ?? [];
    }

    public function removePage($page)
    {
        $id = $page instanceof PageInterface ? $page->id() : $page;
        if (null === ($this->pageids[$id] ?? null)) {
            return;
        }
        $index = $this->pageids[$id];
        unset($this->pages[$index]);
        unset($this->pageids[$id]);
    }

    /**
     * Add a list of pages to the pages prorperty
     * 
     * @param array<array<string,mixed>>|PageInterface[] $pages 
     * 
     * @return void 
     */
    public function addPages(array $pages)
    {
        foreach ($pages as $page) {
            $this->addPage($page);
        }
    }

    public function setCompleteCallbackUrl(string $url)
    {
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Expect parameter of ' . __METHOD__ . ' to be a RFC 2396 - Uniform Resource Identifiers (URI) compliant');
        }
        $this->completeCallbackUrl = $url;
        return $this;
    }

    public function getCompleteCallbackUrl()
    {
        return $this->completeCallbackUrl;
    }
}
