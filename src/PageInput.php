<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use Drewlabs\MyriadUssdBrowserSdk\Contracts\Arrayable;
use Drewlabs\MyriadUssdBrowserSdk\Contracts\InputInterface;
use InvalidArgumentException;

class PageInput implements InputInterface, Arrayable
{
    /**
     * The request method used when submitting input
     * 
     * @var string
     */
    private $method;

    /**
     * The endpoint to which the input value is submitted by default
     * 
     * In case page on which the input is put has links, the link $to
     * property is preferred
     * 
     * @var string
     */
    private $to;

    /**
     * Input type attribute. Default to 'text'.
     * 
     * Possible values are text|alphanum|digits
     * 
     * @var string
     */
    private $type;

    /**
     * The name attribute of the input
     * 
     * @var string
     */
    private $name;

    /**
     * Defines the maximum length or max value in case of digits of the input
     * 
     * @var int
     */
    private $max;

    /**
     * Defines the minimum length or max value in case of digits of the input
     * 
     * @var int
     */
    private $min;

    /**
     * Mask the input value to the USSD browser
     * 
     * @var bool
     */
    private $masked = false;

    /**
     * Creates a page input instance
     * 
     * @param string $name 
     * @param string $type 
     * @param string $method 
     * @param int $length 
     * @param string|null $to 
     */
    public function __construct(
        string $name,
        $type = 'text',
        string $to = null,
        string $method = 'GET',
        int $max = 255,
        int $min = 0
    ) {
        $this->name($name);
        $this->type($type);
        $this->method($method ?? 'GET');
        $this->endpoint($to);
        $this->constraints($max, $min);
    }

    /**
     * Creates a page input instance
     * 
     * @param array $attributes 
     * @return static 
     * @throws InvalidArgumentException 
     */
    public static function fromArray(array $attributes)
    {
        Assert::assertRequiredKeys($attributes, ['name']);
        return new static(
            $attributes['name'],
            $attributes['type'],
            $attributes['url'],
            $attributes['method'] ?? 'GET',
            $attributes['max'] ?? 255,
            $attributes['min'] ?? 1
        );
    }

    public function method(string $value = null)
    {
        if (null !== $value) {
            $this->method = $value;
        }
        return $this->method;
    }

    public function endpoint(string $value = null)
    {
        if (null !== $value) {
            $this->to = $value;
        }
        return $this->to;
    }

    public function type(string $value = null)
    {
        if (null !== $value) {
            Assert::in($value, ['text', 'alphanum', 'digits']);
            $this->type = $value;
        }
        return $this->type;
    }

    public function name(string $value = null)
    {
        if (null !== $value) {
            $this->name = $value;
        }
        return $this->name;
    }

    public function masked()
    {
        $this->masked = true;
        return $this;
    }

    public function constraints(int $max = null, int $min = null)
    {
        if (null !== $max) {
            $this->max = $max;
            $this->min = $min;
        }
        return [$this->max, $this->min];
    }

    public function toArray()
    {
        return array_filter([
            'method' => $this->method ?? 'GET',
            'url' => $this->to,
            'type' => 'digits' === strtolower($this->type ?? '') && $this->min && $this->max ?
                "$this->type[" . implode(',', [$this->min, $this->max]) . "]" :
                $this->type ?? 'text',
            'input' => $this->name ?? 'user-entry',
            'password' => boolval($this->masked),
            'width' => $this->max
        ], function ($item) {
            return null !== $item;
        });
    }
}
