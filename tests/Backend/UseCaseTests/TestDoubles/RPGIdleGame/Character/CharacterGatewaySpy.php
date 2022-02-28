<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharactersListView;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharacterView;

class CharacterGatewaySpy implements CharacterGateway, CharacterViewGateway
{
    /** @var array<string, Character> */
    private array $characters = [];

    public function save(Character $character): void
    {
        $this->characters[$character->id()->value()] = $character;
    }

    public function findOneById(CharacterId $characterId): ?Character
    {
        return $this->characters[$characterId->value()] ?? null;
    }

    public function findOneByIdAndOwner(CharacterId $characterId, CharacterOwner $requester): ?Character
    {
        if (false === $this->has($characterId->value())
            || false === $this->characters[$characterId->value()]->owner()->equals($requester)) {
            return null;
        }

        return $this->characters[$characterId->value()];
    }

    public function viewOneById(string $characterId, string $requesterId): JsonableCharacterView
    {
        if (false === $this->has($characterId)
            || $this->characters[$characterId]->owner()->value() !== $requesterId
            || false === $this->characters[$characterId]->activeStatus()->isActive()
        ) {
            throw new CharacterNotFoundException();
        }

        $character = $this->characters[$characterId];

        return JsonableCharacterView::fromSource(self::characterToArray($character));
    }

    public function viewAllForOwner(string $ownerUuid): JsonableCharactersListView
    {
        $filterForOwner = static function (Character $character) use ($ownerUuid) {
            return $character->owner()->value() === $ownerUuid && true === $character->activeStatus()->isActive();
        };

        return JsonableCharactersListView::fromSource(
            array_map(
                [$this, 'characterToArray'],
                array_filter($this->characters, $filterForOwner)
            ),
        );
    }

    public function has(string $characterId): bool
    {
        return array_key_exists($characterId, $this->characters);
    }

    /**
     * @return array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int, created_on: int, available_as_of: int}
     */
    private static function characterToArray(Character $character): array
    {
        return [
            'id'              => $character->id()->value(),
            'name'            => $character->name()->value(),
            'owner'           => $character->owner()->value(),
            'skill_points'    => $character->skillPoint()->value(),
            'health'          => $character->health()->value(),
            'attack'          => $character->attack()->value(),
            'defense'         => $character->defense()->value(),
            'magik'           => $character->magik()->value(),
            'rank'            => $character->rank()->value(),
            'fights_count'    => 0,
            'wins_count'      => 0,
            'draws_count'     => 0,
            'losses_count'    => 0,
            'created_on'      => $character->creationDate()->value()->getTimestamp(),
            'available_as_of' => $character->availability()->value()->getTimestamp(),
        ];
    }
}
