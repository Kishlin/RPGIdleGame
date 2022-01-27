<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeletionAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;

class CharacterGatewaySpy implements CharacterGateway, DeletionAllowanceGateway, CharacterViewGateway
{
    /** @var array<string, Character> */
    private array $characters = [];

    public function save(Character $character): void
    {
        $this->characters[$character->characterId()->value()] = $character;
    }

    public function delete(CharacterId $characterId): void
    {
        unset($this->characters[$characterId->value()]);
    }

    public function findOneById(CharacterId $characterId): ?Character
    {
        return $this->characters[$characterId->value()] ?? null;
    }

    public function viewOneById(string $characterId): CompleteCharacterView
    {
        if (false === $this->has($characterId)) {
            throw new CharacterNotFoundException();
        }

        $character = $this->characters[$characterId];

        return CompleteCharacterView::fromEntity($character);
    }

    public function viewAllForOwner(string $ownerUuid): array
    {
        $filterForOwner = static function (Character $character) use ($ownerUuid) {
            return $character->characterOwner()->value() === $ownerUuid;
        };

        return array_map(
            [CompleteCharacterView::class, 'fromEntity'],
            array_filter($this->characters, $filterForOwner)
        );
    }

    public function requesterIsTheRightfulOwner(CharacterOwner $deletionRequester, CharacterId $characterId): bool
    {
        if (false === $this->has($characterId->value())) {
            return false;
        }

        return $deletionRequester->equals($this->characters[$characterId->value()]->characterOwner());
    }

    public function has(string $characterId): bool
    {
        return array_key_exists($characterId, $this->characters);
    }
}