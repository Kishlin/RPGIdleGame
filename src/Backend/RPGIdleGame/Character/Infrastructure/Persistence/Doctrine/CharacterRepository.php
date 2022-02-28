<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterActiveStatus;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class CharacterRepository extends DoctrineRepository implements CharacterGateway
{
    public function save(Character $character): void
    {
        $this->entityManager->persist($character);
        $this->entityManager->flush();
    }

    public function findOneById(CharacterId $characterId): ?Character
    {
        $criteria = ['id' => $characterId, 'activeStatus' => new CharacterActiveStatus(true)];

        return $this->entityManager->getRepository(Character::class)->findOneBy($criteria);
    }

    public function findOneByIdAndOwner(CharacterId $characterId, CharacterOwner $requester): ?Character
    {
        $criteria = ['id' => $characterId, 'owner' => $requester, 'activeStatus' => new CharacterActiveStatus(true)];

        return $this->entityManager->getRepository(Character::class)->findOneBy($criteria);
    }
}
