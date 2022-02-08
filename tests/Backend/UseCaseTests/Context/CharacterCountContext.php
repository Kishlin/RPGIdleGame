<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use ReflectionException;

final class CharacterCountContext extends RPGIdleGameContext
{
    /**
     * @Given /^it has reached the character limit$/
     *
     * @throws ReflectionException
     */
    public function itHasReachedTheCharacterLimit(): void
    {
        $this
            ->container()
            ->characterCountGatewaySpy()
            ->manuallyOverrideCountForOwner(new AccountId(self::CLIENT_UUID), CharacterCount::CHARACTER_LIMIT)
        ;
    }
}
