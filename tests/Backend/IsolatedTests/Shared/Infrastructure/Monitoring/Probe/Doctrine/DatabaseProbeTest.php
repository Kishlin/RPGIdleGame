<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Monitoring\Probe\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\Doctrine\DatabaseProbe;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\Doctrine\DatabaseProbe
 */
final class DatabaseProbeTest extends TestCase
{
    public function testItIsAliveWhenConnected(): void
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $connection->method('isConnected')->willReturn(true);

        $entityManager = $this->getMockForAbstractClass(EntityManagerInterface::class);
        $entityManager->method('getConnection')->willReturn($connection);

        $probe = new DatabaseProbe($entityManager);

        self::assertTrue($probe->isAlive());
    }

    public function testItIsNotAliveOnFailedConnection(): void
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $connection->method('connect')->willThrowException(new Exception());

        $entityManager = $this->getMockForAbstractClass(EntityManagerInterface::class);
        $entityManager->method('getConnection')->willReturn($connection);

        $probe = new DatabaseProbe($entityManager);

        self::assertFalse($probe->isAlive());
    }
}
