<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Tools\Test\Contract;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\RPGIdleGame\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\RPGIdleGameEntityManagerFactory;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Abstract TestCase for Contract Tests of Repositories, child classes of \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository.
 *
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository
 */
abstract class RepositoryContractTestCase extends TestCase
{
    private static ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::entityManager()->beginTransaction();
    }

    protected function tearDown(): void
    {
        if (null !== self::$entityManager) {
            self::$entityManager->rollback();
            self::$entityManager->close();

            self::$entityManager = null;
        }
    }

    protected static function entityManager(): EntityManagerInterface
    {
        if (null === self::$entityManager) {
            self::$entityManager = self::createEntityManager();
        }

        return self::$entityManager;
    }

    protected static function loadFixtures(AggregateRoot ...$aggregates): void
    {
        foreach ($aggregates as $aggregateRoot) {
            self::entityManager()->persist($aggregateRoot);
        }

        self::entityManager()->flush();
    }

    protected static function execute(string $sql): void
    {
        try {
            self::entityManager()->getConnection()->executeStatement($sql);
        } catch (Throwable $e) {
            self::fail($e->getMessage());
        }
    }

    private static function createEntityManager(): EntityManagerInterface
    {
        try {
            return RPGIdleGameEntityManagerFactory::create(
                ['url' => $_ENV['DATABASE_URL']],
                'test'
            );
        } catch (Throwable $e) {
            self::fail('Failed to create an entity manager: ' . $e->getMessage());
        }
    }
}
