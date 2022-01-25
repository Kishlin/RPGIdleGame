<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools;

use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Provides helper methods to call private methods or read internal properties in tests.
 */
final class ReflectionHelper
{
    /**
     * Calls a method on a given subject and returns the result.
     * Method call can be made with args, given as additional parameters.
     *
     * <code>
     * $result = ReflectionHelper::invoke($object, 'method');
     * $result = ReflectionHelper::invoke($object, 'method', $paramOne, $paramTwo);
     * </code>
     *
     * @throws ReflectionException
     */
    public static function invoke(object $subject, string $method, mixed ...$args): mixed
    {
        $reflectionMethod = new ReflectionMethod($subject, $method);

        return $reflectionMethod->invoke($subject, ...$args);
    }

    /**
     * Reads and return the value of an internal property, in the given subject.
     *
     * @throws ReflectionException
     */
    public static function propertyValue(object $subject, string $property): mixed
    {
        $reflectionProperty = new ReflectionProperty($subject::class, $property);

        return $reflectionProperty->getValue($subject);
    }

    /**
     * Writes into an internal property, in the given subject.
     *
     * @throws ReflectionException
     */
    public static function writePropertyValue(object $subject, string $property, mixed $value): void
    {
        $reflectionProperty = new ReflectionProperty($subject::class, $property);

        $reflectionProperty->setValue($subject, $value);
    }
}
