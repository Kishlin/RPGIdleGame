<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use PHPUnit\Framework\Assert;
use ReflectionException;

trait CharacterCountTrait
{
    /**
     * @Then /^a fresh character counter is registered$/
     *
     * @throws ReflectionException
     */
    public function aFreshCharacterCounterIsRegistered(): void
    {
        assert(null !== $this->accountId);

        $characterCount = $this->container->characterCountGatewaySpy()->countForOwner($this->accountId);

        Assert::assertNotNull($characterCount);
        Assert::assertEquals(0, $characterCount->value());
    }
}
