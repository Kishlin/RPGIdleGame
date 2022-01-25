<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\RepositorySpy;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreationAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCountGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountReachedLimit;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use ReflectionException;

final class CharacterCountGatewaySpy implements CharacterCountGateway, CreationAllowanceGateway
{
    /** @var array<string, CharacterCount> */
    private array $characterCounts = [];

    /**
     * @throws ReflectionException
     */
    public function save(CharacterCount $characterCount): void
    {
        $owner = ReflectionHelper::propertyValue($characterCount, 'characterCountOwner');
        assert($owner instanceof CharacterCountOwner);

        $this->characterCounts[$owner->value()] = $characterCount;
    }

    public function findForOwner(CharacterCountOwner $characterCountOwner): ?CharacterCount
    {
        return $this->characterCounts[$characterCountOwner->value()] ?? null;
    }

    /**
     * @throws ReflectionException
     */
    public function isAllowedToCreateACharacter(UuidValueObject $characterCountOwner): bool
    {
        if (false === array_key_exists($characterCountOwner->value(), $this->characterCounts)) {
            return false;
        }

        $characterCountReachedLimit = ReflectionHelper::propertyValue(
            $this->characterCounts[$characterCountOwner->value()],
            'characterCountReachedLimit'
        );

        assert($characterCountReachedLimit instanceof CharacterCountReachedLimit);

        return false === $characterCountReachedLimit->value();
    }

    /**
     * @throws ReflectionException
     */
    public function countForOwnerEquals(UuidValueObject $ownerId, int $count): bool
    {
        return array_key_exists($ownerId->value(), $this->characterCounts)
            && $count === $this->countValueFromAggregateRoot($this->characterCounts[$ownerId->value()])->value()
        ;
    }

    /**
     * @throws ReflectionException
     */
    public function manuallyOverrideCountForOwner(UuidValueObject $owner, int $count): void
    {
        $characterCount = $this->characterCounts[$owner->value()];

        $characterCountValue        = new CharacterCountValue($count);
        $characterCountReachedLimit = new CharacterCountReachedLimit($count >= CharacterCount::CHARACTER_LIMIT);

        ReflectionHelper::writePropertyValue($characterCount, 'characterCountValue', $characterCountValue);
        ReflectionHelper::writePropertyValue($characterCount, 'characterCountReachedLimit', $characterCountReachedLimit);
    }

    /**
     * @throws ReflectionException
     */
    private function countValueFromAggregateRoot(CharacterCount $characterCount): CharacterCountValue
    {
        $characterCountValue = ReflectionHelper::propertyValue($characterCount, 'characterCountValue');
        assert($characterCountValue instanceof CharacterCountValue);

        return $characterCountValue;
    }
}
