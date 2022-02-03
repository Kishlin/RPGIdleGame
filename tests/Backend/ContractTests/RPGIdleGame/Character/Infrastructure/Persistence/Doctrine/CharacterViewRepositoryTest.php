<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\SerializableCharacterView;
use Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\CharacterViewRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\CharacterViewRepository
 */
final class CharacterViewRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws CharacterNotFoundException|Exception|ReflectionException
     */
    public function testItCanViewACharacter(): void
    {
        $character = CharacterProvider::completeCharacter();
        self::loadFixtures($character);

        $repository = new CharacterViewRepository(self::entityManager());

        $view = $repository->viewOneById($character->id()->value(), $character->owner()->value());

        self::assertInstanceOf(SerializableCharacterView::class, $view);
    }

    /**
     * @throws CharacterNotFoundException|Exception|ReflectionException
     */
    public function testItCannotViewACharacterFromSomeoneElse(): void
    {
        $character = CharacterProvider::completeCharacter();
        self::loadFixtures($character);

        $repository = new CharacterViewRepository(self::entityManager());

        self::expectException(CharacterNotFoundException::class);
        $repository->viewOneById($character->id()->value(), 'invalid-owner');
    }

    /**
     * @dataProvider complexDatasetProvider
     *
     * @throws Exception|ReflectionException
     */
    public function testItCanViewCharactersForOwner(
        Character $characterOneOfOwnerOne,
        Character $characterTwoOfOwnerOne,
        Character $characterOneOfOwnerTwo,
        CharacterOwner $ownerOne,
        CharacterOwner $ownerTwo,
    ): void {
        self::loadFixtures($characterOneOfOwnerOne, $characterTwoOfOwnerOne, $characterOneOfOwnerTwo);

        $repository = new CharacterViewRepository(self::entityManager());

        $characterViewsForOwnerOne = $repository->viewAllForOwner($ownerOne->value());

        self::assertCount(2, $characterViewsForOwnerOne);
        self::assertSame(
            $characterOneOfOwnerOne->id()->value(),
            ReflectionHelper::propertyValue($characterViewsForOwnerOne[0], 'id'),
        );
        self::assertSame(
            $characterTwoOfOwnerOne->id()->value(),
            ReflectionHelper::propertyValue($characterViewsForOwnerOne[1], 'id'),
        );

        $characterViewsForOwnerTwo = $repository->viewAllForOwner($ownerTwo->value());

        self::assertCount(1, $characterViewsForOwnerTwo);
        self::assertSame(
            $characterOneOfOwnerTwo->id()->value(),
            ReflectionHelper::propertyValue($characterViewsForOwnerTwo[0], 'id'),
        );
    }

    /**
     * @noinspection PhpDocSignatureInspection
     *
     * @return iterable<array<Character|CharacterOwner>>
     */
    public function complexDatasetProvider(): iterable
    {
        $ownerOne = new CharacterOwner('83539398-2758-4194-a98c-bd693f0aa987');
        $ownerTwo = new CharacterOwner('8fd7312d-04e8-4b56-89eb-403647652e77');

        $characterIdOne   = new CharacterId('7afed1fc-d670-4588-b4b6-e192f618c25e');
        $characterIdTwo   = new CharacterId('aefd0f4d-6083-430c-a86e-eb88f02a71f1');
        $characterIdThree = new CharacterId('f35cfe72-41ad-4406-af46-d8305414751d');

        yield [
            Character::createFresh($characterIdOne, new CharacterName('Owner 1, char 1'), $ownerOne),
            Character::createFresh($characterIdTwo, new CharacterName('Owner 1, char 2'), $ownerOne),
            Character::createFresh($characterIdThree, new CharacterName('Owner 2, char 1'), $ownerTwo),
            $ownerOne,
            $ownerTwo,
        ];
    }
}
