<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\Apps\AbstractIntegrationTests\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Integration Test to verify the service container holds an active database connection.
 *
 * @internal
 * @coversNothing
 */
abstract class CoreDatabaseServiceTestCase extends WebTestCase
{
    /**
     * @param ContainerInterface $container The service container which should hold an active database service
     * @param string             $serviceId The id of the service. Defaults to \Doctrine\ORM\EntityManagerInterface if auto-wired.
     *
     * @throws ContainerExceptionInterface|Exception|NotFoundExceptionInterface
     */
    protected static function assertItHasAnActiveDatabaseConnection(
        ContainerInterface $container,
        string $serviceId = EntityManagerInterface::class
    ): void {
        /** @var ?EntityManagerInterface $entityManager */
        $entityManager = $container->get($serviceId);

        if (null === $entityManager) {
            self::fail('Failed to get the database service from the container.');
        }

        $connection = $entityManager->getConnection();
        $connection->connect();

        self::assertTrue($connection->isConnected());
    }
}
