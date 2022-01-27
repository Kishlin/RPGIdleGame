<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineViewer
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }
}
