<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\Backoffice\Tools;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\RPGIdleGame\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\RPGIdleGameEntityManagerFactory;
use Kishlin\Backend\Shared\Domain\Aggregate\AggregateRoot;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

abstract class BackofficeWebTestCase extends WebTestCase
{
    use BackofficeKernelTestCaseTrait;

    private static ?EntityManagerInterface $entityManager = null;

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if (null !== self::$entityManager) {
            self::entityManager()->getConnection()->executeStatement(<<<'SQL'
DELETE FROM fight_turns;
DELETE FROM fights;
DELETE FROM fight_initiators;
DELETE FROM fight_opponents;
DELETE FROM characters;
DELETE FROM character_counts;
DELETE FROM accounts;
SQL);

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
