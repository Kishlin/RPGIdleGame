<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

final class Tools
{
    public static function startsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if (0 === $length) {
            return true;
        }

        return substr($haystack, 0, $length) === $needle;
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if (0 === $length) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }

    public static function fromPascalToSnakeCase(string $input): string
    {
        $stringWithUnderscores = preg_replace('/(?<!^)[A-Z]/', '_$0', $input);

        if (null === $stringWithUnderscores) {
            throw new RuntimeException("Failed to transform {$input}");
        }

        return strtolower($stringWithUnderscores);
    }

    /**
     * @param class-string<object>|object $objectOrClass
     *
     * @throws ReflectionException
     */
    public static function shortClassName(string|object $objectOrClass): string
    {
        $reflectionClass = new ReflectionClass($objectOrClass);

        return $reflectionClass->getShortName();
    }
}
