<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\View;

use JsonException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListItem;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightListItem
 */
final class JsonableFightListItemTest extends TestCase
{
    public function testItCanBeCreatedFromSource(): void
    {
        $view = JsonableFightListItem::fromSource($this->source());

        self::assertInstanceOf(JsonableFightListItem::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromSource
     *
     * @throws JsonException
     */
    public function testItCanBeConvertedToJson(): void
    {
        $view = JsonableFightListItem::fromSource($this->source());

        self::assertSame(self::json(), $view->toJson());
    }

    /**
     * @return array{id: string, winner_id: string, initiator_name: string, initiator_rank: int, opponent_name: string, opponent_rank: int}
     */
    private static function source(): array
    {
        return [
            'id'             => 'fight-0',
            'winner_id'      => 'character-0',
            'initiator_name' => 'Kishlin',
            'initiator_rank' => 26,
            'opponent_name'  => 'Brawler',
            'opponent_rank'  => 20,
        ];
    }

    private static function json(): string
    {
        return <<<'JSON'
{"id":"fight-0","winner_id":"character-0","initiator_name":"Kishlin","initiator_rank":26,"opponent_name":"Brawler","opponent_rank":20}
JSON;
    }
}
