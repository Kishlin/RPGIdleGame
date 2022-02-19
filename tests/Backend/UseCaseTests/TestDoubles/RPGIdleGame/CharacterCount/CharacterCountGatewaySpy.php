<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\CharacterCount;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreationAllowanceGateway;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreationLimitCheckerDoesNotExistException;
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

    public function save(CharacterCount $characterCount): void
    {
        $this->characterCounts[$characterCount->owner()->value()] = $characterCount;
    }

    public function findForOwner(CharacterCountOwner $characterCountOwner): ?CharacterCount
    {
        return $this->characterCounts[$characterCountOwner->value()] ?? null;
    }

    public function ownerHasReachedCharacterLimit(UuidValueObject $characterOwner): bool
    {
        if (false === array_key_exists($characterOwner->value(), $this->characterCounts)) {
            throw new CreationLimitCheckerDoesNotExistException();
        }

        return $this->characterCounts[$characterOwner->value()]->reachedLimit()->value();
    }

    public function countForOwnerEquals(UuidValueObject $ownerId, int $count): bool
    {
        return array_key_exists($ownerId->value(), $this->characterCounts)
            && $count === $this->characterCounts[$ownerId->value()]->count()->value()
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

        ReflectionHelper::writePropertyValue($characterCount, 'count', $characterCountValue);
        ReflectionHelper::writePropertyValue($characterCount, 'reachedLimit', $characterCountReachedLimit);
    }
}
