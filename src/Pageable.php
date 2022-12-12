<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\LinkInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageCallbackInterface;
use InvalidArgumentException;
use UnexpectedValueException;

trait Pageable
{


    /**
     * Page component id
     * 
     * @var string|int
     */
    private $id;

    /**
     * List of page links
     * 
     * @var LinkInterface[]
     */
    private $links = [];

    /**
     * 
     * @var InputInterface
     */
    private $input;

    /**
     * Page component description property
     * 
     * @var Stringable|string
     */
    private $ui_message;

    /**
     * Page component title property
     * 
     * @var string|Stringable
     */
    private $title;

    /**
     * Page component split property
     * 
     * @var bool
     */
    private $split_space = false;

    /**
     * The language used when displaying page to the end user
     * 
     * @var string
     */
    private $lang = 'en';

    /**
     * Set property to false to disable page caching
     * 
     * @var bool
     */
    private $disable_cache = false;


    /**
     * Page property to control if the page will have navigation menu
     * 
     * @var bool
     */
    private $disable_navigation = false;

    /**
     * Page property that controls page menu status
     * 
     * @var bool
     */
    private $disable_menu = false;

    /**
     * The page is stored in the session history and accessible
     * 
     * @var bool
     */
    private $disable_history = false;

    /**
     * If this attribute is set to true, the USSD session will be 
     * closed after the display of this page and the user will not
     * be able to respond
     * 
     * @var bool
     */
    private $ends_session = false;


    public function id()
    {
        return $this->id;
    }

    public function disableMenu()
    {
        $this->disable_menu = true;
        return $this;
    }

    public function disableNavigation()
    {
        $this->disable_navigation = true;
        return $this;
    }

    public function disableCache()
    {
        $this->disable_cache = true;
        return $this;
    }

    public function disableHistory()
    {
        $this->disable_history = true;
        return $this;
    }

    public function useLang(string $lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function splitOnSpace()
    {
        $this->split_space = true;
        return $this;
    }

    public function title($title = null)
    {
        if (null !== $title) {
            $this->assertIsStringable($title, __METHOD__);
            $this->title = $title;
        }
        return $this->title;
    }

    public function uiMessage($message = null)
    {
        if (null !== $message) {
            $this->assertIsStringable($message, __METHOD__);
            $this->ui_message = $message;
        }
        return $this->ui_message;
    }

    public function setInput($value)
    {
        Assert::assertTypeOf($value, ['array', InputInterface::class]);
        $this->input = is_array($value) ? PageInput::fromArray($value) : $value;
        return $this;
    }

    public function addLink($link)
    {
        $link_ = is_array($link) ? Link::fromArray($link) : $link;
        if (!($link_ instanceof LinkInterface)) {
            throw new InvalidArgumentException('Expect all items of the links argument must be of type ' . LinkInterface::class);
        }
        $this->links[] = $link_;
        return $this;
    }

    public function links(array $links)
    {
        foreach ($links as $link) {
            $this->addLink($link);
        }
    }

    public function toArray()
    {
        if ($this->callback && (false === filter_var($callback = (string)$this->callback, FILTER_VALIDATE_URL))) {
            throw new UnexpectedValueException('Page callback __toString() function must return valid url');
        }
        return array_filter([

            // UI components
            'title' => (string)$this->title,
            'message' => (string)$this->ui_message,
            'form' => $this->input ? $this->input->toArray() : null,
            'callback' => $callback ? $callback : null,

            // Build page links array
            'links' => array_map(function ($link) {
                return $link->toArray();
            }, array_filter($this->links ?? [])),

            // Page attributes
            'page' => [
                // UI attributes
                'split_space' => boolval($this->split_space),
                'history' => !boolval($this->disable_history),
                'menu' => !boolval($this->disable_menu),
                'language' => $this->lang ?? 'en',
                'volatile' => boolval($this->disable_cache),
                'navigation_keywords' => !boolval($this->disable_navigation),
                'session_end' => boolval($this->ends_session),
            ]

        ], function ($item) {
            return $item !== null;
        });
    }

    /**
     * Set the page attribtues values
     * 
     * @param array $attributes 
     * @return void 
     * @throws UnexpectedValueException 
     * @throws InvalidArgumentException 
     */
    private function setPageAttrributes(array $attributes)
    {
        // Disable page menu
        if (boolval($attributes['disable_menu'] ?? false)) {
            $this->disableMenu();
        }

        // Disable page caching
        if (boolval($attributes['disable_cache'] ?? false)) {
            $this->disableCache();
        }

        // Disable page navigation keyboard
        if (boolval($attributes['disable_navigation'] ?? false)) {
            $this->disableNavigation();
        }

        // Set the page language
        if (isset($attributes['lang'])) {
            $this->useLang($attributes['lang']);
        }
    }

    private function assertIsStringable($value, string $method)
    {
        Assert::assertTypeOf($value, ['string', Stringable::class, \Stringable::class], $method);
    }
}
