<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightInitiatorRepository;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\Constraint\FightParticipantRepresentsTheCharacterConstraint;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightInitiatorRepository
 */
final class FightInitiatorRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception|FightInitiatorNotFoundException
     */
    public function testItCanComputeAFightInitiatorFromItsExternalDetails(): void
    {
        $character = CharacterProvider::freshCharacter();

        self::loadFixtures($character);

        $repository = new FightInitiatorRepository(new UuidGeneratorUsingRamsey(), self::entityManager());
        $initiator  = $repository->createFromExternalDetailsOfInitiator($character->id());

        self::assertFightInitiatorRepresentsTheCharacter($character, $initiator);
    }

    /**
     * @throws Exception
     */
    public function testItThrowsAnExceptionWhenItDoesNotExist(): void
    {
        $repository = new FightInitiatorRepository(new UuidGeneratorUsingRamsey(), self::entityManager());

        self::expectException(FightInitiatorNotFoundException::class);
        $repository->createFromExternalDetailsOfInitiator(new CharacterId('f980d452-16d7-420b-9883-e247647b25d0'));
    }

    public static function assertFightInitiatorRepresentsTheCharacter(Character $expected, FightInitiator $actual): void
    {
        self::assertThat($actual, new FightParticipantRepresentsTheCharacterConstraint($expected));
    }
}
