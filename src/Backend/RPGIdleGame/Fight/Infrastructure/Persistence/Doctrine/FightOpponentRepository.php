<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponentGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\NoOpponentAvailableException;
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

final class FightOpponentRepository extends DoctrineRepository implements FightOpponentGateway
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
WHERE id != :id
ORDER BY abs(rank - (SELECT rank FROM characters WHERE id = :id LIMIT 1)) ASC,
(
    SELECT count(fights.id)
    FROM fights
    LEFT JOIN fight_initiators ON fights.initiator = fight_initiators.id
    LEFT JOIN fight_opponents ON fights.opponent = fight_opponents.id
    WHERE (
        (fight_initiators.character_id = characters.id AND fight_opponents.character_id = :id)
        OR (fight_initiators.character_id = :id AND fight_opponents.character_id = characters.id)
    )
) ASC
LIMIT 1
SQL;

    public function __construct(
        protected UuidGenerator $uuidGenerator,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception|NoOpponentAvailableException
     */
    public function createFromExternalDetailsOfAnAvailableOpponent(UuidValueObject $initiatorId): FightOpponent
    {
        /**
         * @var array{character_id: string, character_health: int, character_attack: int, character_defense: int, character_magik: int, character_rank: int}|false $data
         */
        $data = $this->entityManager->getConnection()->fetchAssociative(self::QUERY, ['id' => $initiatorId]);

        if (false === $data) {
            throw new NoOpponentAvailableException();
        }

        return FightOpponent::create(
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
