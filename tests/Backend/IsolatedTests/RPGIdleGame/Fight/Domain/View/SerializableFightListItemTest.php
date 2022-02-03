<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightListItem;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightListItem
 */
final class SerializableFightListItemTest extends TestCase
{
    public function testItCanBeCreatedFromSource(): void
    {
        $source = $this->source();

        $view = SerializableFightListItem::fromSource($source);

        self::assertInstanceOf(SerializableFightListItem::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromSource
     */
    public function testItCanBeSerialized(): void
    {
        $view = SerializableFightListItem::fromSource($this->source());

        self::assertSame(self::sourceSerialized(), serialize($view));
    }

    public function testItCanBeUnserialized(): void
    {
        self::assertInstanceOf(SerializableFightListItem::class, unserialize($this->sourceSerialized()));
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

    private static function sourceSerialized(): string
    {
        return <<<'TXT'
O:71:"Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightListItem":6:{s:2:"id";s:7:"fight-0";s:9:"winner_id";s:11:"character-0";s:14:"initiator_name";s:7:"Kishlin";s:14:"initiator_rank";i:26;s:13:"opponent_name";s:7:"Brawler";s:13:"opponent_rank";i:20;}
TXT;
    }
}
