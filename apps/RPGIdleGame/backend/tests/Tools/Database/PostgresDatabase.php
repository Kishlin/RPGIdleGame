<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\ORMException;
use Kishlin\Backend\RPGIdleGame\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\RPGIdleGameEntityManagerFactory;
use ReflectionException;

final class PostgresDatabase implements DatabaseInterface
{
    private ?Connection $connection = null;

    /**
     * @throws Exception|ORMException|ReflectionException
     */
    public function refreshDatabase(array $tables): void
    {
        self::connection()->executeStatement(
            'DELETE FROM ' . implode('; DELETE FROM ', $tables) . ';',
        );
    }

    /**
     * @throws Exception|ORMException|ReflectionException
     */
    public function fetchOne(string $query, array $params = []): mixed
    {
        $result = self::connection()->fetchOne($query, $params);

        return false !== $result ? $result : null;
    }

    /**
     * @throws Exception|ORMException|ReflectionException
     */
    public function fetchAssociative(string $query, array $params = []): array|null
    {
        return self::connection()->fetchAssociative($query, $params) ?: null;
    }

    /**
     * @throws Exception|ORMException|ReflectionException
     */
    public function fetchAllAssociative(string $query, array $params = []): array|null
    {
        return self::connection()->fetchAllAssociative($query, $params) ?: null;
    }

    /**
     * @throws Exception|ORMException|ReflectionException
     */
    public function exec(string $query, array $params = []): void
    {
        self::connection()->executeStatement($query, $params);
    }

    /**
     * @throws Exception|ORMException|ReflectionException
     */
    private function connection(): Connection
    {
        if (null === $this->connection) {
            $entityManager = RPGIdleGameEntityManagerFactory::create(
                parameters: [
                    'dbname'   => $_ENV['DB_NAME'],
                    'host'     => $_ENV['DB_HOST'],
                    'port'     => $_ENV['DB_PORT'],
                    'user'     => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'driver'   => $_ENV['DB_DRIVER'],
                ],
                environment: 'test'
            );

            $this->connection = $entityManager->getConnection();
            $this->connection()->connect();
        }

        return $this->connection;
    }
}
