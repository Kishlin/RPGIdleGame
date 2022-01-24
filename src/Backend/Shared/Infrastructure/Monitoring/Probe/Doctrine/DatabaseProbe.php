<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\Probe;

final class DatabaseProbe implements Probe
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function name(): string
    {
        return 'database';
    }

    public function isAlive(): bool
    {
        $connection = $this->entityManager->getConnection();

        try {
            $connection->connect();
        } catch (Exception $e) {
            return false;
        }

        return $connection->isConnected();
    }
}
