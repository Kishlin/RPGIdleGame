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

    public function hasReachedLimit(CharacterCountOwner $characterCountOwner, int $countLimit): bool
    {
        $hasFoundACountAtOrOverLimit = $this->entityManager->getConnection()->fetchOne(
            'SELECT 1 FROM character_counts WHERE owner_id = :owner AND character_count >= :limit LIMIT 1;',
            [
                'owner' => $characterCountOwner->value(),
                'limit' => $countLimit,
            ],
        );

        return false !== $hasFoundACountAtOrOverLimit;
    }
}
