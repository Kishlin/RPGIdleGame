<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
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
}
