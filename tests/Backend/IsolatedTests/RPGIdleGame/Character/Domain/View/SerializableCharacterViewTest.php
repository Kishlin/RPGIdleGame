<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\View;

use Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView;
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
     * @return array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}
     */
    private function source(): array
    {
        return [
            'id'           => '30fb4cf4-00b9-4e01-88c7-88bf9612eaf1',
            'name'         => 'Kishlin',
            'owner'        => '10e5a3e9-6ab9-41ba-9f80-898a233b020b',
            'skill_points' => 5,
            'health'       => 12,
            'attack'       => 15,
            'defense'      => 3,
            'magik'        => 5,
            'rank'         => 12,
            'fights_count' => 20,
            'wins_count'   => 15,
            'draws_count'  => 2,
            'losses_count' => 3,
        ];
    }

    private static function sourceSerialized(): string
    {
        return <<<'TXT'
O:75:"Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView":13:{s:2:"id";s:36:"30fb4cf4-00b9-4e01-88c7-88bf9612eaf1";s:4:"name";s:7:"Kishlin";s:5:"owner";s:36:"10e5a3e9-6ab9-41ba-9f80-898a233b020b";s:12:"skill_points";i:5;s:6:"health";i:12;s:6:"attack";i:15;s:7:"defense";i:3;s:5:"magik";i:5;s:4:"rank";i:12;s:12:"fights_count";i:20;s:10:"wins_count";i:15;s:11:"draws_count";i:2;s:12:"losses_count";i:3;}
TXT;
    }

    /**
     * @noinspection PhpDocSignatureInspection
     *
     * @param array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int} $source
     *
     * @throws ReflectionException
     */
    private static function assertViewRepresentsSource(array $source, SerializableCharacterView $view): void
    {
        self::assertSame($source['id'], ReflectionHelper::propertyValue($view, 'id'));
        self::assertSame($source['name'], ReflectionHelper::propertyValue($view, 'name'));
        self::assertSame($source['owner'], ReflectionHelper::propertyValue($view, 'owner'));
        self::assertSame($source['skill_points'], ReflectionHelper::propertyValue($view, 'skillPoints'));
        self::assertSame($source['health'], ReflectionHelper::propertyValue($view, 'health'));
        self::assertSame($source['attack'], ReflectionHelper::propertyValue($view, 'attack'));
        self::assertSame($source['defense'], ReflectionHelper::propertyValue($view, 'defense'));
        self::assertSame($source['magik'], ReflectionHelper::propertyValue($view, 'magik'));
        self::assertSame($source['rank'], ReflectionHelper::propertyValue($view, 'rank'));
        self::assertSame($source['fights_count'], ReflectionHelper::propertyValue($view, 'fightsCount'));
        self::assertSame($source['wins_count'], ReflectionHelper::propertyValue($view, 'winsCount'));
        self::assertSame($source['draws_count'], ReflectionHelper::propertyValue($view, 'drawsCount'));
        self::assertSame($source['losses_count'], ReflectionHelper::propertyValue($view, 'lossesCount'));
    }
}
