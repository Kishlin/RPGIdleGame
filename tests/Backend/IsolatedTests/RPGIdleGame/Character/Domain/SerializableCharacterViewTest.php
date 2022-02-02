<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView
 */
final class SerializableCharacterViewTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testItCanBeCreatedFromSource(): void
    {
        $source = $this->source();

        $view = SerializableCharacterView::fromSource($source);

        self::assertViewRepresentsSource($source, $view);
    }

    /**
     * @throws ReflectionException
     */
    public function testItCanBeCreatedFromEntity(): void
    {
        $character = CharacterProvider::completeCharacter();

        $view = SerializableCharacterView::fromEntity($character);

        self::assertViewRepresentsCharacter($character, $view);
    }

    /**
     * @depends testItCanBeCreatedFromSource
     */
    public function testItCanBeSerialized(): void
    {
        $view = SerializableCharacterView::fromSource($this->source());

        self::assertSame(self::sourceSerialized(), serialize($view));
    }

    public function testItCanBeUnserialized(): void
    {
        self::assertInstanceOf(SerializableCharacterView::class, unserialize($this->sourceSerialized()));
    }

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
    private function source(): array
    {
        return [
            'character_id'           => '30fb4cf4-00b9-4e01-88c7-88bf9612eaf1',
            'character_name'         => 'Kishlin',
            'character_owner'        => '10e5a3e9-6ab9-41ba-9f80-898a233b020b',
            'character_skill_points' => 5,
            'character_health'       => 12,
            'character_attack'       => 15,
            'character_defense'      => 3,
            'character_magik'        => 5,
            'character_rank'         => 12,
            'character_fights_count' => 8,
        ];
    }

    private static function sourceSerialized(): string
    {
        return <<<'TXT'
O:75:"Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView":10:{s:12:"character_id";s:36:"30fb4cf4-00b9-4e01-88c7-88bf9612eaf1";s:14:"character_name";s:7:"Kishlin";s:15:"character_owner";s:36:"10e5a3e9-6ab9-41ba-9f80-898a233b020b";s:22:"character_skill_points";i:5;s:16:"character_health";i:12;s:16:"character_attack";i:15;s:17:"character_defense";i:3;s:15:"character_magik";i:5;s:14:"character_rank";i:12;s:22:"character_fights_count";i:8;}
TXT;
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
     *
     * @throws ReflectionException
     */
    private static function assertViewRepresentsSource(array $source, SerializableCharacterView $view): void
    {
        self::assertSame($source['character_id'], ReflectionHelper::propertyValue($view, 'id'));
        self::assertSame($source['character_name'], ReflectionHelper::propertyValue($view, 'name'));
        self::assertSame($source['character_owner'], ReflectionHelper::propertyValue($view, 'owner'));
        self::assertSame($source['character_skill_points'], ReflectionHelper::propertyValue($view, 'skillPoints'));
        self::assertSame($source['character_health'], ReflectionHelper::propertyValue($view, 'health'));
        self::assertSame($source['character_attack'], ReflectionHelper::propertyValue($view, 'attack'));
        self::assertSame($source['character_defense'], ReflectionHelper::propertyValue($view, 'defense'));
        self::assertSame($source['character_magik'], ReflectionHelper::propertyValue($view, 'magik'));
        self::assertSame($source['character_rank'], ReflectionHelper::propertyValue($view, 'rank'));
        self::assertSame($source['character_fights_count'], ReflectionHelper::propertyValue($view, 'fightsCount'));
    }

    /**
     * @throws ReflectionException
     */
    private static function assertViewRepresentsCharacter(Character $character, SerializableCharacterView $view): void
    {
        self::assertSame($character->characterId()->value(), ReflectionHelper::propertyValue($view, 'id'));
        self::assertSame($character->characterName()->value(), ReflectionHelper::propertyValue($view, 'name'));
        self::assertSame($character->characterOwner()->value(), ReflectionHelper::propertyValue($view, 'owner'));
        self::assertSame($character->characterSkillPoint()->value(), ReflectionHelper::propertyValue($view, 'skillPoints'));
        self::assertSame($character->characterHealth()->value(), ReflectionHelper::propertyValue($view, 'health'));
        self::assertSame($character->characterAttack()->value(), ReflectionHelper::propertyValue($view, 'attack'));
        self::assertSame($character->characterDefense()->value(), ReflectionHelper::propertyValue($view, 'defense'));
        self::assertSame($character->characterMagik()->value(), ReflectionHelper::propertyValue($view, 'magik'));
        self::assertSame($character->characterRank()->value(), ReflectionHelper::propertyValue($view, 'rank'));
        self::assertSame($character->characterFightsCount()->value(), ReflectionHelper::propertyValue($view, 'fightsCount'));
    }
}
