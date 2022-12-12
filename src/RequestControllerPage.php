<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageCallbackInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestControllerInterface;
use InvalidArgumentException;

class RequestControllerPage implements PageInterface, RequestControllerInterface
{

    use Pageable;

    /**
     * List of listeners of the page component
     * 
     * @var PageCallbackInterface
     */
    private $callback;

    /**
     * Creates an instance of page component
     * 
     * @param string|int $id 
     * @param Stringable|string $title 
     * @param Stringable|string $description 
     * @param PageCallbackInterface $callback 
     * @param InputInterface|array|null $input
     * @throws InvalidArgumentException 
     */
    public function __construct(
        $id,
        $title,
        $description,
        PageCallbackInterface $callback,
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
        $this->setCallback($callback);
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
        Assert::assertRequiredKeys($attributes, ['id', 'title', 'description', 'callback']);

        // Create the instance with default properties
        $object = new static(
            $attributes['id'],
            $attributes['title'],
            $attributes['description'],
            $attributes['callback'],
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

    /**
     * Add a callback that is invoked wheb the page 
     * activity is completed by the user
     * 
     * @param PageCallbackInterface|callable $callback 
     * 
     * @return self
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback) && !($callback instanceof PageCallbackInterface)) {
            throw new InvalidArgumentException('Page listener must be a PHP callable or an instance of ' . PageCallbackInterface::class);
        }

        if (!($callback instanceof PageCallbackInterface)) {
            $callback = new class($callback) implements PageCallbackInterface
            {
                /**
                 * Injected callable instance
                 * 
                 * @var callable
                 */
                private $callable;

                /**
                 * Createsa a page callback instance
                 * 
                 * @param callable $callable 
                 */
                public function __construct(callable $callable)
                {
                    $this->callable = $callable;
                }

                public function __invoke(...$args)
                {
                    return ($this->callable)(...$args);
                }
            };
        }
        $this->callback = $callback;
        return $this;
    }


    public function onRequest($request)
    {
        return $this->callback->__invoke($request);
    }
}
