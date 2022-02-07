<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterStats\Domain;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStats;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;
use Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\CharacterStats\Domain\Constraint\ItRecordedStatsConstraint;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStats
 */
final class CharacterStatsTest extends TestCase
{
    public function testItCanBeInitialized(): void
    {
        $characterStats = CharacterStats::initiate(new CharacterStatsCharacterId('0dc479fe-90e1-4d6c-85b5-1648e05ef659'));

        self::assertItRecordedStats(wins: 0, draws: 0, losses: 0, stats: $characterStats);
    }

    public function testItCanBeCreatedWithScalars(): void
    {
        $wins   = 5;
        $draws  = 9;
        $losses = 3;

        $id             = new CharacterStatsCharacterId('c934101b-0551-44f5-be1c-d08ee563d1a0');
        $characterStats = CharacterStats::fromScalars($id, $wins, $draws, $losses);

        self::assertItRecordedStats($wins, $draws, $losses, $characterStats);
    }

    public static function assertItRecordedStats(int $wins, int $draws, int $losses, CharacterStats $stats): void
    {
        self::assertThat($stats, new ItRecordedStatsConstraint(wins: $wins, draws: $draws, losses: $losses));
    }
}
