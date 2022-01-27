<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\NotEnoughSkillPointsException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint
 */
final class CharactersSkillPointTest extends TestCase
{
    public function testItCanEarnASkillPoint(): void
    {
        $skillPoints = new CharacterSkillPoint(10);

        self::assertSame(11, $skillPoints->earnASkillPoint()->value());
    }

    public function testItCanRemoveSkillPoints(): void
    {
        $skillPoints = new CharacterSkillPoint(10);

        self::assertSame(5, $skillPoints->removeSkillPoints(5)->value());
    }

    public function testItCannotRemoveMoreSkillPointsThanAvailable(): void
    {
        $skillPoints = new CharacterSkillPoint(10);

        $this->expectException(NotEnoughSkillPointsException::class);
        $skillPoints->removeSkillPoints(100);
    }

    public function testPointsRemovalRefusesNegativeAmounts(): void
    {
        $skillPoints = new CharacterSkillPoint(10);

        $this->expectException(InvalidValueException::class);
        $skillPoints->removeSkillPoints(-5);
    }
}
