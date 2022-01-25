<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountValue;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine\CharacterCountRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterCountProvider;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine\CharacterCountRepository
 */
final class CharacterCountRepositoryTest extends RepositoryContractTestCase
{
    public function testItCanSaveACharacterCount(): void
    {
        $characterCount = CharacterCountProvider::freshCount();

        $repository = new CharacterCountRepository(self::entityManager());

        $repository->save($characterCount);

        self::assertCount(1, self::entityManager()->getRepository(CharacterCount::class)->findAll());
    }

    /**
     * @dataProvider characterCountWithSurroundingLimitsProvider
     */
    public function testItCanDetectWhenItReachedTheLimit(
        CharacterCount $characterCount,
        CharacterCountOwner $characterCountOwner,
        int $exactLimit,
        int $limitBelowCount,
        int $limitAboveCount
    ): void {
        self::loadFixtures($characterCount);

        $repository = new CharacterCountRepository(self::entityManager());

        self::assertTrue($repository->hasReachedLimit($characterCountOwner, $exactLimit));
        self::assertTrue($repository->hasReachedLimit($characterCountOwner, $limitBelowCount));
        self::assertFalse($repository->hasReachedLimit($characterCountOwner, $limitAboveCount));
    }

    /**
     * @noinspection PhpDocSignatureInspection
     *
     * @throws ReflectionException
     *
     * @return iterable<array<CharacterCount|CharacterCountOwner|int>>
     */
    public function characterCountWithSurroundingLimitsProvider(): iterable
    {
        $characterCount = CharacterCountProvider::countWithAFewCharacters();

        $characterCountOwner = ReflectionHelper::propertyValue($characterCount, 'characterCountOwner');
        $characterCountValue = ReflectionHelper::propertyValue($characterCount, 'characterCountValue');
        assert($characterCountOwner instanceof CharacterCountOwner);
        assert($characterCountValue instanceof CharacterCountValue);
        $value = $characterCountValue->value();

        yield [
            $characterCount,
            $characterCountOwner,
            $characterCountValue->value(),
            $characterCountValue->value() - 1,
            $value + 1,
        ];
    }
}
