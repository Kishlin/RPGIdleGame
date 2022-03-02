<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterAvailability;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorIsRestingException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiatorNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightInitiatorRepository;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Backend\Shared\Infrastructure\Time\FrozenClock;
use Kishlin\Backend\Shared\Infrastructure\Time\SystemClock;
use Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\Constraint\FightParticipantRepresentsTheCharacterConstraint;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;
use ReflectionException;

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

        $repository = new FightInitiatorRepository(new UuidGeneratorUsingRamsey(), self::entityManager(), new SystemClock());
        $initiator  = $repository->createFromExternalDetailsOfInitiator($character->id());

        self::assertFightInitiatorRepresentsTheCharacter($character, $initiator);
    }

    /**
     * @throws Exception
     */
    public function testItThrowsAnExceptionWhenItDoesNotExist(): void
    {
        $repository = new FightInitiatorRepository(new UuidGeneratorUsingRamsey(), self::entityManager(), new SystemClock());

        self::expectException(FightInitiatorNotFoundException::class);
        $repository->createFromExternalDetailsOfInitiator(new CharacterId('f980d452-16d7-420b-9883-e247647b25d0'));
    }

    /**
     * @throws Exception|ReflectionException
     */
    public function testItThrowsAnExceptionWhenTheFighterIsResting(): void
    {
        $refDateTime = new DateTimeImmutable('1993-11-22 01:00:00');
        $justAfter   = new DateTimeImmutable('1993-11-22 01:00:01');
        $frozenClock = new FrozenClock(frozenTime: $refDateTime);

        $characterJustAvailableId = 'd7cf62ad-2b54-49ae-b336-796004ba07ab';
        $characterStillRestingId  = '403dd4d5-085e-4c3b-b0e1-2c1516bcd991';

        $characterJustAvailable = $this->character(id: $characterJustAvailableId, availableAsOf: $refDateTime);
        $characterStillResting  = $this->character(id: $characterStillRestingId, availableAsOf: $justAfter);

        self::loadFixtures($characterJustAvailable, $characterStillResting);

        $repository = new FightInitiatorRepository(new UuidGeneratorUsingRamsey(), self::entityManager(), $frozenClock);

        $shouldBeOk = $repository->createFromExternalDetailsOfInitiator(new CharacterId($characterJustAvailableId));

        self::assertFightInitiatorRepresentsTheCharacter($characterJustAvailable, $shouldBeOk);

        self::expectException(FightInitiatorIsRestingException::class);
        $repository->createFromExternalDetailsOfInitiator(new CharacterId($characterStillRestingId));
    }

    public static function assertFightInitiatorRepresentsTheCharacter(Character $expected, FightInitiator $actual): void
    {
        self::assertThat($actual, new FightParticipantRepresentsTheCharacterConstraint($expected));
    }

    /**
     * @throws ReflectionException
     */
    private function character(string $id, DateTimeImmutable $availableAsOf): Character
    {
        $overrides = [
            'id'           => new CharacterId($id),
            'availability' => new CharacterAvailability($availableAsOf),
        ];

        return CharacterProvider::customCharacter($overrides);
    }
}
