<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Domain\View;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\Shared\Domain\View\SerializableView;

final class SerializableCharacterView extends SerializableView
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

    /**
     * @return array{
     *     character_id:           string,
     *     character_name:         string,
     *     character_owner:        string,
     *     character_skill_points: int,
     *     character_health:       int,
     *     character_attack:       int,
     *     character_defense:      int,
     *     character_magik:        int,
     *     character_rank:         int,
     *     character_fights_count: int,
     * }
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
     * @noinspection PhpDocSignatureInspection
     *
     * @param array{
     *     character_id:           string,
     *     character_name:         string,
     *     character_owner:        string,
     *     character_skill_points: int,
     *     character_health:       int,
     *     character_attack:       int,
     *     character_defense:      int,
     *     character_magik:        int,
     *     character_rank:         int,
     *     character_fights_count: int,
     * } $data
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
     * @noinspection PhpDocSignatureInspection
     *
     * @param array{
     *     character_id:           string,
     *     character_name:         string,
     *     character_owner:        string,
     *     character_skill_points: int,
     *     character_health:       int,
     *     character_attack:       int,
     *     character_defense:      int,
     *     character_magik:        int,
     *     character_rank:         int,
     *     character_fights_count: int,
     * } $source
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

        $view->id          = $character->characterId()->value();
        $view->name        = $character->characterName()->value();
        $view->owner       = $character->characterOwner()->value();
        $view->skillPoints = $character->characterSkillPoint()->value();
        $view->health      = $character->characterHealth()->value();
        $view->attack      = $character->characterAttack()->value();
        $view->defense     = $character->characterDefense()->value();
        $view->magik       = $character->characterMagik()->value();
        $view->rank        = $character->characterRank()->value();
        $view->fightsCount = $character->characterFightsCount()->value();

        return $view;
    }
}
