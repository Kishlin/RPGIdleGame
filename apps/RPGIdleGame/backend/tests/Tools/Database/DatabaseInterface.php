<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\Database;

interface DatabaseInterface
{
    /**
     * @param array<int, string> $tables
     */
    public function refreshDatabase(array $tables): void;

    /**
     * @param array<string, mixed> $params
     *
     * @return mixed null if the query failed, the result otherwise
     */
    public function fetchOne(string $query, array $params = []): mixed;

    /**
     * @param array<string, mixed> $params
     *
     * @return null|array<string, mixed> null if the query failed, the result as an associative array otherwise
     */
    public function fetchAssociative(string $query, array $params = []): array|null;

    /**
     * @param array<string, mixed> $params
     *
     * @return null|array<array<string, mixed>> null if the query failed, the result as an array of associative array otherwise
     */
    public function fetchAllAssociative(string $query, array $params = []): array|null;

    /**
     * @param array<string, mixed> $params
     */
    public function exec(string $query, array $params = []): void;
}
