<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\RepositorySpy;

use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCountGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use ReflectionException;

final class CharacterCountGatewaySpy implements CharacterCountGateway
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

    /**
     * @return ?CharacterCountValue the CharacterCount for the owner if there is one stored, null if there are none
     *
     * @throws ReflectionException
     */
    public function countForOwner(AccountId $ownerId): ?CharacterCountValue
    {
        return array_key_exists($ownerId->value(), $this->characterCounts) ?
            $this->countValueFromAggregateRoot($this->characterCounts[$ownerId->value()]) :
            null
        ;
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
