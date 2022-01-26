<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine\CharacterCountRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterCountProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

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
     * @noinspection PhpDocSignatureInspection
     *
     * @return iterable<array<CharacterCount|CharacterCountOwner>>
     */
    public function characterCountWithSurroundingLimitsProvider(): iterable
    {
        $characterCountBelowLimit = CharacterCountProvider::countWithAFewCharacters();
        $characterCountAtLimit    = CharacterCountProvider::countAtTheLimitOfCharacters();

        $ownerBelowLimit = $characterCountBelowLimit->characterCountOwner();
        $ownerAtLimit    = $characterCountAtLimit->characterCountOwner();

        yield [
            $ownerBelowLimit,
            $ownerAtLimit,
            $characterCountBelowLimit,
            $characterCountAtLimit,
        ];
    }
}
