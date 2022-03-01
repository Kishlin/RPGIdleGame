<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;
use PHPUnit\Framework\Assert;

final class CharacterStatsContext extends RPGIdleGameContext
{
    /**
     * @Then /^the fighting stats of both participants where updated$/
     */
    public function theFightingStatsOfBothParticipantsWhereUpdated(): void
    {
        $gatewaySpy = self::container()->characterStatsGatewaySpy();

        foreach ([self::FIGHTER_UUID, self::OPPONENT_UUID] as $participantCharacterId) {
            $characterStats = $gatewaySpy->findForCharacter(new CharacterStatsCharacterId(self::FIGHTER_UUID));

            Assert::assertNotNull($characterStats);
            Assert::assertSame(1, $characterStats->fightsCount()->value());
            Assert::assertSame(1, $characterStats->winsCount()->value() + $characterStats->lossesCount()->value());
        }
    }
}
