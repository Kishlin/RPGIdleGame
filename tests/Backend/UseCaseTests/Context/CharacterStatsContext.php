<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;
use PHPUnit\Framework\Assert;

final class CharacterStatsContext extends RPGIdleGameContext
{
    private const FIGHTER_UUID  = 'fa2e098a-1ed4-4ddb-91d1-961e0af7143b';
    private const OPPONENT_UUID = 'e26b33be-5253-4cc3-8480-a15e80f18b7a';

    /**
     * @Given /^the fighting stats of both participants where updated$/
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
