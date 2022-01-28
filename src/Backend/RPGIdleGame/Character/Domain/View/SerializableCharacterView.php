<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\View;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Serializable;

final class SerializableCharacterView implements Serializable
{
    private string $id;
    private string $name;
    private string $owner;
    private int $skillPoints;
    private int $health;
    private int $attack;
    private int $defense;
    private int $magik;
    private int $rank;
    private int $fightsCount;

    private function __construct()
    {
    }

    /**
     * @return array<string, int|string>
     * @noinspection PhpDocSignatureInspection
     */
    public function __serialize(): array
    {
        return [
            'character_id'           => $this->id,
            'character_name'         => $this->name,
            'character_owner'        => $this->owner,
            'character_skill_points' => $this->skillPoints,
            'character_health'       => $this->health,
            'character_attack'       => $this->attack,
            'character_defense'      => $this->defense,
            'character_magik'        => $this->magik,
            'character_rank'         => $this->rank,
            'character_fights_count' => $this->fightsCount,
        ];
    }

    /**
     * @param array<string, int|string> $data
     */
    public function __unserialize(array $data): void
    {
        [
            'character_id'           => $this->id,
            'character_name'         => $this->name,
            'character_owner'        => $this->owner,
            'character_skill_points' => $this->skillPoints,
            'character_health'       => $this->health,
            'character_attack'       => $this->attack,
            'character_defense'      => $this->defense,
            'character_magik'        => $this->magik,
            'character_rank'         => $this->rank,
            'character_fights_count' => $this->fightsCount,
        ] = $data;
    }

    /**
     * @param array<string, int|string> $source
     */
    public static function fromSource(array $source): self
    {
        $view = new self();

        $view->__unserialize($source);

        return $view;
    }

    public static function fromEntity(Character $character): self
    {
        $view = new self();

        $view->__unserialize([
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

        return $view;
    }

    public function serialize(): string
    {
        $serialized = json_encode($this->__serialize());
        assert(false !== $serialized);

        return $serialized;
    }

    public function unserialize(string $data): self
    {
        /** @var array<string, int|string> $source */
        $source = json_decode($data, true);

        $view = new self();
        $view->__unserialize($source);

        return $view;
    }
}
