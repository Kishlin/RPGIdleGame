<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCountGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CharacterCountRepository extends DoctrineRepository implements CharacterCountGateway
{
    public function save(CharacterCount $characterCount): void
    {
        $this->entityManager->persist($characterCount);
        $this->entityManager->flush();
    }

    public function findForOwner(CharacterCountOwner $characterCountOwner): ?CharacterCount
    {
        $criteria = ['owner' => $characterCountOwner];

        return $this->entityManager->getRepository(CharacterCount::class)->findOneBy($criteria);
    }
}
