<?php

namespace Drewlabs\MyriadUssdBrowserSdk\Contracts;

interface PageInterface extends Arrayable
{
    /**
     * Returns the id of this instance of the page component
     * 
     * @return string|int 
     */
    public function id();

    /**
     * Returns a page instance with the provided page id value
     * 
     * This method MUST be implemented in such a way as to retain the
     * immutability of the page, and MUST return an instance that has the
     * new and/or updated id.
     * 
     * @var string|int $id
     * 
     * @return self 
     */
    public function withPageId($id);

    /**
     * Add a menu to the page component
     * 
     * @return static 
     */
    public function disableMenu();

    /**
     * Indicates if the page component requires navigation keywords
     * 
     * For implementation classes, page navigation must be enabled by default.
     * 
     * @return static 
     */
    public function disableNavigation();

    /**
     * Disable page caching for the service
     * 
     * For implementation classes, page caching must be enabled by default.
     * 
     * @return static 
     */
    public function disableCache();

    /**
     * The page is stored in the session history and accessible.
     * 
     * For implementation classes, page history must be enabled by default.
     * 
     * @return static 
     */
    public function disableHistory();

    /**
     * Set the language to use for the page session
     * 
     * @param string $lang 
     * 
     * @return static 
     */
    public function useLang(string $lang);

    /**
     * Split the page down on new line and spaces.
     * 
     * @return static 
     */
    public function splitOnSpace();

    /**
     * Page title getter and setter method
     * 
     * @param string|Stringable|null $title 
     * 
     * @return string|Stringable 
     */
    public function title($title =  null);

    /**
     * Page description getter and setter method
     * 
     * @param string|Stringable|null $description
     * 
     * @return string|Stringable 
     */
    public function uiMessage($description = null);

    /**
     * Add an input to the page component
     * 
     * @param InputInterface|array $input
     * 
     * @return static 
     */
    public function setInput($input);

    /**
     * Add a link to the page component
     * 
     * @param LinkInterface|array $link 
     * 
     * @return static 
     */
    public function addLink($link);

    /**
     * Add a list of links to the page component
     * 
     * @param LinkInterface[] $links
     * 
     * @return LinkInterface[] 
     */
    public function links(array $links);
}
