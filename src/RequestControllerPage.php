<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\CompletesUSSDSession as MyriadUssdBrowserSdkCompletesUSSDSession;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\EndsUSSDSession;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageCallbackInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\PageInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestControllerInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\RequestInterface;
use InvalidArgumentException;

class RequestControllerPage implements
    PageInterface,
    RequestControllerInterface,
    EndsUSSDSession
{

    use USSDPageTrait, MyriadUssdBrowserSdkCompletesUSSDSession;

    /**
     * List of listeners of the page component
     * 
     * @var PageCallbackInterface
     */
    private $callback;

    /**
     * Creates an instance of page component
     * 
     * @param PageCallbackInterface $callback 
     * @param string|int|null $id 
     * @param Stringable|string|null $title 
     * @param Stringable|string|null $description
     * @param InputInterface|array|null $input
     * @throws InvalidArgumentException 
     */
    public function __construct(
        PageCallbackInterface $callback,
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
        Assert::assertRequiredKeys($attributes, ['callback']);

        // Create the instance with default properties
        $object = new static(
            $attributes['callback'],
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

    public function onRequest(RequestInterface $request)
    {
        return $this->callback->__invoke($request);
    }
}
