<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\CharacterRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\CharacterRepository
 */
final class CharacterRepositoryTest extends RepositoryContractTestCase
{
    public function testItCanSaveAndRetrieveACharacter(): void
    {
        $character  = CharacterProvider::freshCharacter();
        $repository = new CharacterRepository(self::entityManager());

        $repository->save($character);

        /** @var Character $savedCharacter */
        $savedCharacter = $repository->findOneById($character->characterId());

        self::assertSame($savedCharacter, $character);
    }

    /**
     * @depends testItCanSaveAndRetrieveACharacter
     */
    public function testItCanDeleteACharacter(): void
    {
        $character  = CharacterProvider::freshCharacter();
        $repository = new CharacterRepository(self::entityManager());

        self::loadFixtures($character);

        self::assertCount(1, self::entityManager()->getRepository(Character::class)->findAll());

        $repository->delete($character->characterId());

        self::assertCount(0, self::entityManager()->getRepository(Character::class)->findAll());
    }

    /**
     * @dataProvider complexDatasetProvider
     */
    public function testItCanRetrieveAllForOwner(
        Character $characterOneOfOwnerOne,
        Character $characterTwoOfOwnerOne,
        Character $characterOneOfOwnerTwo,
        CharacterOwner $ownerOne,
        CharacterOwner $ownerTwo,
    ): void {
        self::loadFixtures($characterOneOfOwnerOne, $characterTwoOfOwnerOne, $characterOneOfOwnerTwo);

        $repository = new CharacterRepository(self::entityManager());

        $charactersForOwnerOne = $repository->findAllForOwner($ownerOne);

        self::assertCount(2, $charactersForOwnerOne);
        self::assertContainsEquals($characterOneOfOwnerOne, $charactersForOwnerOne);
        self::assertContainsEquals($characterTwoOfOwnerOne, $charactersForOwnerOne);

        $charactersForOwnerTwo = $repository->findAllForOwner($ownerTwo);

        self::assertCount(1, $charactersForOwnerTwo);
        self::assertContainsEquals($characterOneOfOwnerTwo, $charactersForOwnerTwo);
    }

    /**
     * @depends testItCanSaveAndRetrieveACharacter
     */
    public function testItCanDetectOwnerAlreadyHasACharacterWithAName(): void
    {
        $character  = CharacterProvider::freshCharacter();
        $repository = new CharacterRepository(self::entityManager());

        $characterName  = $character->characterName();
        $characterOwner = $character->characterOwner();

        self::assertFalse($repository->ownerAlreadyHasACharacterWithName($characterName, $characterOwner));

        self::loadFixtures($character);

        self::assertTrue($repository->ownerAlreadyHasACharacterWithName($characterName, $characterOwner));
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
