<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeletionAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterViewer;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;

class CharacterGatewaySpy implements CharacterGateway, DeletionAllowanceGateway, CharacterViewer
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

    public function findAllForOwner(CharacterOwner $characterOwner): array
    {
        $filterForOwner = static function (Character $character) use ($characterOwner) {
            return $characterOwner->equals($character->characterOwner());
        };

        return array_filter($this->characters, $filterForOwner);
    }

    public function ownerAlreadyHasACharacterWithName(CharacterName $characterName, CharacterOwner $characterOwner): bool
    {
        foreach ($this->characters as $character) {
            if (
                $characterName->equals($character->characterName())
                && $characterOwner->equals($character->characterOwner())
            ) {
                return true;
            }
        }

        return false;
    }

    public function viewOneById(string $characterId): CompleteCharacterView
    {
        if (false === $this->has($characterId)) {
            throw new CharacterNotFoundException();
        }

        $character = $this->characters[$characterId];

        return CompleteCharacterView::fromSource([
            'character_id'           => $character->characterId()->value(),
            'character_name'         => $character->characterName()->value(),
            'character_owner'        => $character->characterOwner()->value(),
            'character_skill_points' => $character->characterSkillPoint()->value(),
            'character_health'       => $character->characterHealth()->value(),
            'character_attack'       => $character->characterAttack()->value(),
            'character_defense'      => $character->characterDefense()->value(),
            'character_magik'        => $character->characterMagik()->value(),
            'character_rank'         => $character->characterRank()->value(),
            'character_fights_count' => $character->characterFightsCount()->value(),
        ]);
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
