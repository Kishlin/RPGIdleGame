<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use DateTimeImmutable;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorIsRestingException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantAttack;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantCharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantDefense;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantMagik;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantRank;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Domain\Time\Clock;
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
    rank as character_rank,
    available_as_of as character_availability
FROM characters
WHERE id = :id
AND is_active = true
LIMIT 1
SQL;

    public function __construct(
        protected UuidGenerator $uuidGenerator,
        EntityManagerInterface $entityManager,
        private Clock $clock,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @throws DBALException|Exception|FightInitiatorNotFoundException
     */
    public function createFromExternalDetailsOfInitiator(UuidValueObject $initiatorId): FightInitiator
    {
        /**
         * @var array{character_id: string, character_health: int, character_attack: int, character_defense: int, character_magik: int, character_rank: int, character_availability: string}|false $data
         */
        $data = $this->entityManager->getConnection()->fetchAssociative(self::QUERY, ['id' => $initiatorId]);

        if (false === $data) {
            throw new FightInitiatorNotFoundException();
        }

        if (new DateTimeImmutable($data['character_availability']) > $this->clock->now()) {
            throw new FightInitiatorIsRestingException();
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
