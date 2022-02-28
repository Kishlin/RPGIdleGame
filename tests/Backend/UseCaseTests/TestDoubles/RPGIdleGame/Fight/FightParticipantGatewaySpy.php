<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FighterId;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FightInitiationAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FightRequesterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorNotFoundException;
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
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character\CharacterGatewaySpy;

final class FightParticipantGatewaySpy implements FightInitiationAllowanceGateway, FightInitiatorGateway, FightOpponentGateway
{
    /**
     * @see FightParticipantGatewaySpy::createFromExternalDetailsOfAnAvailableOpponent()
     */
    private const OPPONENT_UUID = 'e26b33be-5253-4cc3-8480-a15e80f18b7a';

    public function __construct(
        private CharacterGatewaySpy $characterGatewaySpy,
    ) {
    }

    public function requesterIsAllowedToFightWithFighter(FightRequesterId $requesterId, FighterId $fighterId): bool
    {
        return null !== $this->characterGatewaySpy->findOneByIdAndOwner(
            CharacterId::fromOther($fighterId),
            CharacterOwner::fromOther($requesterId),
        );
    }

    public function createFromExternalDetailsOfInitiator(UuidValueObject $initiatorId): FightInitiator
    {
        $character = $this->characterGatewaySpy->findOneById(CharacterId::fromOther($initiatorId));
        if (null === $character) {
            throw new FightInitiatorNotFoundException();
        }

        return FightInitiator::create(
            new FightParticipantCharacterId($character->id()->value()),
            new FightParticipantId('68e80eaf-fba4-4d1a-9207-c48ab9f38b0a'),
            new FightParticipantHealth($character->health()->value()),
            new FightParticipantAttack($character->attack()->value()),
            new FightParticipantDefense($character->defense()->value()),
            new FightParticipantMagik($character->magik()->value()),
            new FightParticipantRank($character->rank()->value()),
        );
    }

    /**
     * The opponent is loaded based on a fixed id.
     * Ensure you fill the database with a character of this exact id, for the gateway to find it.
     * Simply do not save any character with this id, if you want the gateway to throw the exception.
     *
     * @see FightParticipantGatewaySpy::OPPONENT_UUID
     */
    public function createFromExternalDetailsOfAnAvailableOpponent(UuidValueObject $initiatorId): FightOpponent
    {
        $character = $this->characterGatewaySpy->findOneById(new CharacterId(self::OPPONENT_UUID));
        if (null === $character) {
            throw new NoOpponentAvailableException();
        }

        return FightOpponent::create(
            new FightParticipantCharacterId($character->id()->value()),
            new FightParticipantId('d24331d2-02e5-42a8-a417-ea772a7b457c'),
            new FightParticipantHealth($character->health()->value()),
            new FightParticipantAttack($character->attack()->value()),
            new FightParticipantDefense($character->defense()->value()),
            new FightParticipantMagik($character->magik()->value()),
            new FightParticipantRank($character->rank()->value()),
        );
    }
}
