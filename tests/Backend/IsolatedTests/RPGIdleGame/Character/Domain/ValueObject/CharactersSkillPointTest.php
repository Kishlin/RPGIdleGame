<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain\ValueObject;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterSkillPoint;
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
}
