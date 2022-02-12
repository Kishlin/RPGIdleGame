<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Fight;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\AbstractFightParticipant;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\CannotAccessFightsException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightTurn;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightViewGateway;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListView;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView;

final class FightGatewaySpy implements FightGateway, FightViewGateway
{
    private const CLIENT_UUID          = '97c116cc-21b0-4624-8e02-88b9b1a977a7';
    private const FIGHTER_UUID         = 'fa2e098a-1ed4-4ddb-91d1-961e0af7143b';
    private const FIGHTER_INITIATOR_ID = '4d248f5f-5f10-49ed-a921-ccdc383acdaf';

    /** @var array<string, Fight> */
    private array $fights = [];

    public function save(Fight $fight): void
    {
        $this->fights[$fight->id()->value()] = $fight;
    }

    public function findOneById(FightId $fightId): ?Fight
    {
        return $this->fights[$fightId->value()] ?? null;
    }

    public function viewOneById(string $fightId, string $requesterId): JsonableFightView
    {
        $fight = $this->fights[$fightId] ?? null;
        if (null === $fight || self::CLIENT_UUID !== $requesterId) {
            throw new FightNotFoundException();
        }

        $turns = $fight->turns();
        assert(is_array($turns));

        $source = [
            'id'        => $fightId,
            'initiator' => self::mapParticipantToArray($fight->initiator()),
            'opponent'  => self::mapParticipantToArray($fight->opponent()),
            'turns'     => array_map([$this, 'mapTurnsToArray'], $turns),
            'winner_id' => $fight->winnerId()->value(),
        ];

        return JsonableFightView::fromSource($source);
    }

    /**
     * {@inheritDoc}
     */
    public function viewAllForFighter(string $fighterId, string $requesterId): JsonableFightListView
    {
        if (self::FIGHTER_UUID === $fighterId && self::CLIENT_UUID !== $requesterId) {
            throw new CannotAccessFightsException();
        }

        return JsonableFightListView::fromSource(
            array_map(
                static fn(Fight $fight) => [
                    'id'             => $fight->id()->value(),
                    'winner_id'      => $fight->winnerId()->value(),
                    'initiator_name' => $fight->initiator()->characterId()->value(),
                    'initiator_rank' => $fight->initiator()->rank()->value(),
                    'opponent_name'  => $fight->opponent()->characterId()->value(),
                    'opponent_rank'  => $fight->opponent()->rank()->value(),
                ],
                array_filter(
                    $this->fights,
                    static fn(Fight $fight) => self::FIGHTER_UUID === $fight->initiator()->characterId()->value()
                        || self::FIGHTER_UUID === $fight->opponent()->characterId()->value(),
                ),
            )
        );
    }

    public function has(string $fightId): bool
    {
        return array_key_exists($fightId, $this->fights);
    }

    /**
     * @return array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}
     */
    private static function mapParticipantToArray(AbstractFightParticipant $participant): array
    {
        if (self::FIGHTER_UUID === $participant->characterId()->value()) {
            $accountUsername = 'Client';
            $characterName   = 'Fighter';
        } else {
            $accountUsername = 'Stranger';
            $characterName   = 'Brawler';
        }

        return [
            'account_username' => $accountUsername,
            'character_name'   => $characterName,
            'health'           => $participant->health()->value(),
            'attack'           => $participant->attack()->value(),
            'defense'          => $participant->defense()->value(),
            'magik'            => $participant->magik()->value(),
            'rank'             => $participant->rank()->value(),
        ];
    }

    /**
     * @return array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}
     */
    private static function mapTurnsToArray(FightTurn $fightTurn): array
    {
        $attackerId    = $fightTurn->attackerId()->value();
        $characterName = self::FIGHTER_INITIATOR_ID === $attackerId ? 'Fighter' : 'Brawler';

        return [
            'character_name'     => $characterName,
            'index'              => $fightTurn->index()->value(),
            'attacker_attack'    => $fightTurn->attackerAttack()->value(),
            'attacker_magik'     => $fightTurn->attackerMagik()->value(),
            'attacker_dice_roll' => $fightTurn->attackerDiceRoll()->value(),
            'defender_defense'   => $fightTurn->defenderDefense()->value(),
            'damage_dealt'       => $fightTurn->damageDealt()->value(),
            'defender_health'    => $fightTurn->defenderHealth()->value(),
        ];
    }
}
