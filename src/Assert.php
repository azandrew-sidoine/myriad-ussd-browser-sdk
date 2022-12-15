<?php

namespace Drewlabs\MyriadUssdBrowserSdk;

use InvalidArgumentException;

class Assert
{
    /**
     * Expect the provided value to be in the list of items in the $haystack
     * 
     * @param mixed $key 
     * @param array $haystack
     * @return void 
     * @throws InvalidArgumentException 
     */
    public static function in($key, array $haystack)
    {
        if (!in_array($key, $haystack)) {
            throw new InvalidArgumentException('Not supported entry provided');
        }
    }

    /**
     * Assert that the argument $value is a stringable instance
     * 
     * @param mixed $value 
     * @param string $method 
     * @return void 
     */
    public static function assertTypeOf($value, array $types, string $method = null)
    {
        foreach ($types as $type) {
            if ((is_scalar($value) || null === $value) && gettype($value) === $type) {
                return;
            }
            if (is_a($value, $type, true)) {
                return;
            }
        }
        throw new InvalidArgumentException(
            null !== $method ?
                ($method . 'parameter must be an instance ' . (implode('|', $types))) :
                'Expects type of ' . (implode('|', $types)) . ', got ' . self::getType($value)
        );
    }

    /**
     * Assert that the value of $value is a PHP array
     * 
     * @param mixed $value 
     * @return void 
     * @throws InvalidArgumentException 
     */
    public static function assertIsArray($value)
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Expect of PHP array got ');
        }
    }

    /**
     * Assert that the required keys provided by the user is present in the value of $value array
     * 
     * @param array $array 
     * @param string[] $keys 
     * @return void 
     * @throws InvalidArgumentException 
     */
    public static function assertRequiredKeys(array $array, array $keys)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                throw new InvalidArgumentException('Required ' . $key . ' is missing from the provided dictionary');
            }
        }
    }

    /**
     * Return the type definition of a given value
     * 
     * @return string 
     */
    public static function getType($value)
    {
        return is_object($value) && null !== $value ? get_class($value) : gettype($value);
    }
}
