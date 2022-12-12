<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageInterface;
use InvalidArgumentException;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\InputInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\Stringable;
use UnexpectedValueException;

class Page implements PageInterface
{
    use Pageable;

    /**
     * Creates an instance of page component
     * 
     * @param string|int $id 
     * @param Stringable|string $title 
     * @param Stringable|string $description 
     * @param InputInterface|array|null $input
     * 
     * @throws InvalidArgumentException 
     */
    public function __construct(
        $id,
        $title,
        $description,
        $input = null
    ) {
        if (!is_string($id) && !is_int($id)) {
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
        Assert::assertRequiredKeys($attributes, ['id', 'title', 'description']);

        // Create the instance with default properties
        $object = new static(
            $attributes['id'],
            $attributes['title'],
            $attributes['description'],
            $attributes['input'] ?? $attributes['form'] ?? null
        );
        // Set the page links
        if (isset($attributes['links'])) {
            $object->links($attributes['links']);
        }

        // Set the page attributes
        $object->setPageAttrributes($attributes);

        // Returns the contructed object
        return $object;
    }
}
