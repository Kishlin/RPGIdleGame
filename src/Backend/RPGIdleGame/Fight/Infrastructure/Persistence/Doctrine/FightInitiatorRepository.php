<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantAttack;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantCharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantDefense;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantMagik;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantRank;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\Repository\DoctrineRepository;

final class FightInitiatorRepository extends DoctrineRepository implements FightInitiatorGateway
{
    private const QUERY = <<<'SQL'
SELECT
       id as character_id,
       health as character_health,
       attack as character_attack,
       defense as character_defense,
       magik as character_magik,
       rank as character_rank
FROM characters
WHERE id = :id
LIMIT 1
SQL;

    public function __construct(
        protected UuidGenerator $uuidGenerator,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @throws Exception|FightInitiatorNotFoundException
     */
    public function createFromExternalDetailsOfInitiator(UuidValueObject $initiatorId): FightInitiator
    {
        /**
         * @var array{character_id: string, character_health: int, character_attack: int, character_defense: int, character_magik: int, character_rank: int}|false $data
         */
        $data = $this->entityManager->getConnection()->fetchAssociative(self::QUERY, ['id' => $initiatorId]);

        if (false === $data) {
            throw new FightInitiatorNotFoundException();
        }

        return FightInitiator::create(
            new FightParticipantCharacterId($data['character_id']),
            new FightParticipantId($this->uuidGenerator->uuid4()),
            new FightParticipantHealth($data['character_health']),
            new FightParticipantAttack($data['character_attack']),
            new FightParticipantDefense($data['character_defense']),
            new FightParticipantMagik($data['character_magik']),
            new FightParticipantRank($data['character_rank']),
        );
    }
}
