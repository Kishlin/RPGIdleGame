<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
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

    public function delete(CharacterId $characterId): void
    {
        $this->entityManager->getConnection()->executeQuery(
            'DELETE FROM characters WHERE id = :id;',
            ['id' => $characterId->value()],
        );
    }

    public function findOneById(CharacterId $characterId): ?Character
    {
        return $this->entityManager->getRepository(Character::class)->findOneBy(['id' => $characterId]);
    }

    public function findOneByIdAndOwner(CharacterId $characterId, CharacterOwner $requester): ?Character
    {
        $criteria = ['id' => $characterId, 'owner' => $requester];

        return $this->entityManager->getRepository(Character::class)->findOneBy($criteria);
    }
}
