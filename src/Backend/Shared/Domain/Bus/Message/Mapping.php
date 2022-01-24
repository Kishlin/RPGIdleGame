<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Message;

/**
 * Help Messages read values from an unknown data source, and ensure all their properties have a value or null expectedly.
 *
 * Messages usually are of \Kishlin\Backend\Shared\Domain\Bus\Command\Command
 *                      or \Kishlin\Backend\Shared\Domain\Bus\Query\Query
 *
 * @see Trait's implementations for details on how and why it's used.
 */
trait Mapping
{
    /**
     * @param array<string, bool|int|string> $source
     */
    private static function getInt(array $source, string $key): int
    {
        return isset($source[$key]) ? (int) $source[$key] : 0;
    }

    /**
     * @param array<string, bool|int|string> $source
     */
    private static function getIntOrNull(array $source, string $key): ?int
    {
        return isset($source[$key]) ? (int) $source[$key] : null;
    }

    /**
     * @param array<string, bool|int|string> $source
     */
    private static function getString(array $source, string $key): string
    {
        return isset($source[$key]) ? (string) $source[$key] : '';
    }

    /**
     * @param array<string, bool|int|string> $source
     */
    private static function getStringOrNull(array $source, string $key): ?string
    {
        return isset($source[$key]) ? (string) $source[$key] : null;
    }

    /**
     * @param array<string, bool|int|string> $source
     */
    private static function getBool(array $source, string $key): bool
    {
        return isset($source[$key]) && $source[$key];
    }

    /**
     * @param array<string, bool|int|string> $source
     */
    private static function getBoolOrNull(array $source, string $key): ?bool
    {
        return isset($source[$key]) ? (bool) $source[$key] : null;
    }
}
