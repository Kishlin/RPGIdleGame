<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\View;

use JsonException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharactersListView;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\View\JsonableCharactersListView
 */
final class JsonableCharactersListViewTest extends TestCase
{
    public function testItCanBeCreatedFromSource(): void
    {
        $view = JsonableCharactersListView::fromSource($this->source());

        self::assertInstanceOf(JsonableCharactersListView::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromSource
     *
     * @throws JsonException
     */
    public function testItCanBeConvertedToJson(): void
    {
        $view = JsonableCharactersListView::fromSource($this->source());

        self::assertSame(self::json(), $view->toJson());
    }

    /**
     * @return array<array{id: string, name: string, owner: string, skill_points: int, health: int, attack: int, defense: int, magik: int, rank: int, fights_count: int, wins_count: int, draws_count: int, losses_count: int}>
     */
    private function source(): array
    {
        return [
            [
                'id'           => '30fb4cf4-00b9-4e01-88c7-88bf9612eaf1',
                'name'         => 'Kishlin',
                'owner'        => 'aaa74d22-6934-42fe-9783-b0ae9173ba0e',
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
            ],
            [
                'id'           => '30fb4cf4-00b9-4e01-88c7-88bf9612eaf1',
                'name'         => 'Character',
                'owner'        => '10e5a3e9-6ab9-41ba-9f80-898a233b020b',
                'skill_points' => 7,
                'health'       => 24,
                'attack'       => 28,
                'defense'      => 13,
                'magik'        => 26,
                'rank'         => 35,
                'fights_count' => 61,
                'wins_count'   => 27,
                'draws_count'  => 5,
                'losses_count' => 28,
            ],
        ];
    }

    private static function json(): string
    {
        return <<<'JSON'
[{"id":"30fb4cf4-00b9-4e01-88c7-88bf9612eaf1","name":"Kishlin","owner":"aaa74d22-6934-42fe-9783-b0ae9173ba0e","skill_points":5,"health":12,"attack":15,"defense":3,"magik":5,"rank":12,"fights_count":20,"wins_count":15,"draws_count":2,"losses_count":3},{"id":"30fb4cf4-00b9-4e01-88c7-88bf9612eaf1","name":"Character","owner":"10e5a3e9-6ab9-41ba-9f80-898a233b020b","skill_points":7,"health":24,"attack":28,"defense":13,"magik":26,"rank":35,"fights_count":61,"wins_count":27,"draws_count":5,"losses_count":28}]
JSON;
    }
}
