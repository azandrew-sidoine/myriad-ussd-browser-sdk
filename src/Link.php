<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\LinkInterface;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\Stringable;
use InvalidArgumentException;
use UnexpectedValueException;

class Link implements LinkInterface
{

    /**
     * Link id
     * 
     * @var int
     */
    private $id;

    /**
     * Link content or description
     * 
     * @var string|Stringable|\Stringable
     */
    private $content;

    /**
     * Endpoint to which request are sent
     * 
     * @var string
     */
    private $to;

    /**
     * Link request method
     * 
     * @var string
     */
    private $method;

    /**
     * Static links are show on all page sections
     * 
     * @var bool
     */
    private $static = false;

    /**
     * Creates a list instance
     * 
     * @param int $id
     * @param mixed $content
     * @param string $to
     * @param string $method
     * 
     * @throws InvalidArgumentException 
     */
    public function __construct(int $id, $content, string $to, string $method = 'GET')
    {
        $this->id($id);
        $this->content($content);
        $this->to($to);
        $this->method($method);
    }

    public function toArray()
    {
        return array_filter([
            'id' => $this->id,
            'content' => (string)$this->content,
            'url' => $this->to,
            'method' => $this->method ?? 'GET',
            'all_pages' => boolval($this->static)
        ], function ($item) {
            return null !== $item;
        });
    }

    /**
     * Creates a Link class from a disctionary of values
     * 
     * @param array $attributes 
     * @return static 
     * @throws UnexpectedValueException 
     */
    public static function fromArray(array $attributes)
    {
        static::assertRequiredProperties($attributes);
        return new static(
            $attributes['id'],
            $attributes['content'],
            $attributes['url'],
            $attributes['method'] ?? 'GET'
        );
    }

    public function id(int $value = null)
    {
        if (null !== $value) {
            $this->id = $value;
        }
        return $this->id;
    }

    public function content($value = null)
    {
        if (null !== $value) {
            $this->assertIsStringable($value);
            $this->content = $value;
        }
        return $this->content;
    }

    public function method(string $value = null)
    {
        if (null !== $value) {
            $this->method = $value;
        }
        return $this->method;
    }

    public function to(string $value = null)
    {
        if (null !== $value) {
            $this->to = $value;
        }
        return $this->to;
    }

    public function asStatic()
    {
        $this->static = true;
        return $this;
    }

    /**
     * Asserts that the value of $value is strigable
     * 
     * @param mixed $value 
     * @param string $method 
     * @return void 
     */
    private function assertIsStringable($value, string $method = null)
    {
        Assert::assertTypeOf($value, ['string', Stringable::class, \Stringable::class], $method);
    }

    /**
     * Assert on required attributes to build a page instance
     * 
     * @param array $attributes 
     * @return void 
     * @throws UnexpectedValueException 
     */
    private static function assertRequiredProperties(array $attributes)
    {
        Assert::assertRequiredKeys($attributes, ['id', 'content', 'url']);
        if ((!is_int($attributes['id'] ?? null))) {
            throw new UnexpectedValueException('Expect the id attribute to be of type int');
        }
    }
}
