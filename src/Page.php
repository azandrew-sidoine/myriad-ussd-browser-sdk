<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\CompletesUSSDSession as MyriadUssdBrowserSdkCompletesUSSDSession;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\EndsUSSDSession;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageInterface;
use InvalidArgumentException;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\InputInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\Stringable;
use UnexpectedValueException;

class Page implements PageInterface, EndsUSSDSession
{
    use USSDPageTrait, MyriadUssdBrowserSdkCompletesUSSDSession;

    /**
     * Creates an instance of page component
     * 
     * @param string|int|null $id 
     * @param Stringable|string|null $title 
     * @param Stringable|string|null $description 
     * @param InputInterface|array|null $input
     * 
     * @throws InvalidArgumentException 
     */
    public function __construct(
        $id = null,
        $title = null,
        $description = null,
        $input = null
    ) {
        if ((null !== $id) && !is_string($id) && !is_int($id)) {
            throw new InvalidArgumentException('Page id must be an instance of string or integer');
        }
        if (null !== $input) {
            $this->setInput($input);
        }
        $this->id = $id;
        $this->title($title);
        $this->uiMessage($description);
    }

    /**
     * Creates a page component from a dictionnary configuration
     * 
     * @param array $attributes 
     * @return static 
     * @throws UnexpectedValueException 
     * @throws InvalidArgumentException 
     */
    public static function fromArray(array $attributes)
    {
        // Create the instance with default properties
        $object = new static(
            $attributes['id'] ?? null,
            $attributes['title'] ?? null,
            $attributes['description'] ?? null,
            $attributes['input'] ?? $attributes['form'] ?? null
        );
        // Set the page links
        if (isset($attributes['links'])) {
            $object->links($attributes['links']);
        }

        // Set the page attributes
        $object->setPageAttributes($attributes);

        // Returns the contructed object
        return $object;
    }
}
