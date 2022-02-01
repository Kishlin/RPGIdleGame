<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class FightRepository extends DoctrineRepository implements FightGateway
{
    public function save(Fight $fight): void
    {
        $this->entityManager->persist($fight);
        $this->entityManager->flush();
    }

    public function findOneById(FightId $fightId): ?Fight
    {
        return $this->entityManager->getRepository(Fight::class)->findOneBy(['id' => $fightId]);
    }
}
