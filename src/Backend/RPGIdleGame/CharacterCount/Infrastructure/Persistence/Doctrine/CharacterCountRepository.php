<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreationAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCountGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CharacterCountRepository extends DoctrineRepository implements CharacterCountGateway, CreationAllowanceGateway
{
    public function save(CharacterCount $characterCount): void
    {
        $this->entityManager->persist($characterCount);
        $this->entityManager->flush();
    }

    public function findForOwner(CharacterCountOwner $characterCountOwner): ?CharacterCount
    {
        $criteria = ['characterCountOwner' => $characterCountOwner];

        return $this->entityManager->getRepository(CharacterCount::class)->findOneBy($criteria);
    }

    public function isAllowedToCreateACharacter(UuidValueObject $characterCountOwner): bool
    {
        $characterCountReachedLimit = $this->entityManager->getConnection()->fetchOne(
            'SELECT character_count_reached_limit FROM character_counts WHERE owner_id = :owner LIMIT 1;',
            ['owner' => $characterCountOwner->value()],
        );

        return false === $characterCountReachedLimit;
    }
}
