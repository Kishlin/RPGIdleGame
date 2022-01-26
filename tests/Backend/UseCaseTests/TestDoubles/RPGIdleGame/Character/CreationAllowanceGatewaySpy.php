<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreationAllowanceGateway;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use ReflectionException;

class CreationAllowanceGatewaySpy implements CreationAllowanceGateway
{
    public function __construct(
        private object $characterCountsDatabase,
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function isAllowedToCreateACharacter(UuidValueObject $characterOwner): bool
    {
        $characterCounts = ReflectionHelper::propertyValue($this->characterCountsDatabase, 'characterCounts');
        assert(is_array($characterCounts));

        if (false === array_key_exists($characterOwner->value(), $characterCounts)) {
            return false;
        }

        return false === $characterCounts[$characterOwner->value()]->characterCountReachedLimit()->value();
    }
}
