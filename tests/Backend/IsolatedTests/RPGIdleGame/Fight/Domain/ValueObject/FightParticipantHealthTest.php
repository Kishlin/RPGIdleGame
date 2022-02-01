<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth
 */
final class FightParticipantHealthTest extends TestCase
{
    public function testItCanDecreaseNotLowerThanZero(): void
    {
        $startHealth = new FightParticipantHealth(10);

        $loweredHealth = $startHealth->removeHealth(8);
        self::assertSame(2, $loweredHealth->value());

        $shouldNotBeNegative = $startHealth->removeHealth(15);
        self::assertSame(0, $shouldNotBeNegative->value());
    }

    public function testItIsAliveUntilHealthIsZero(): void
    {
        self::assertTrue((new FightParticipantHealth(5))->isStillAlive());

        self::assertFalse((new FightParticipantHealth(0))->isStillAlive());
    }
}
