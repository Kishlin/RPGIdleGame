<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterRank;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\NoOpponentAvailableException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantAttack;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantDefense;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantHealth;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantMagik;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantRank;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightOpponentRepository;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\Constraint\FightParticipantRepresentsTheCharacterConstraint;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightOpponentRepository
 */
final class FightOpponentRepositoryTest extends RepositoryContractTestCase
{
    private static ?UuidGenerator $uuidGenerator = null;

    /**
     * @throws Exception|NoOpponentAvailableException
     */
    public function testItThrowsAnExceptionIfTNoOpponentIsAvailable(): void
    {
        $initiator  = CharacterProvider::freshCharacter();
        $repository = new FightOpponentRepository(self::uuidGenerator(), self::entityManager());

        self::loadFixtures($initiator);

        self::expectException(NoOpponentAvailableException::class);
        $repository->createFromExternalDetailsOfAnAvailableOpponent($initiator->characterId());
    }

    /**
     * @throws Exception|NoOpponentAvailableException
     */
    public function testItDoesNotPickTheInitiatorItself(): void
    {
        $initiator  = CharacterProvider::freshCharacter();
        $other      = $this->otherCharacter('1deda0c4-8815-4f2d-8d38-14e431732b21');
        $repository = new FightOpponentRepository(self::uuidGenerator(), self::entityManager());

        self::loadFixtures($initiator, $other);

        $opponent = $repository->createFromExternalDetailsOfAnAvailableOpponent($initiator->characterId());

        self::assertFightOpponentRepresentsTheCharacter($other, $opponent);
    }

    /**
     * @throws Exception|NoOpponentAvailableException
     */
    public function testItPicksTheCharacterWhoseRankIsClosestToTheInitiator(): void
    {
        $otherWithDistantRank = $this->otherCharacterWithADistantRank();
        $otherWithCloseRank   = $this->otherCharacterWithACloseRank();

        $initiator  = CharacterProvider::freshCharacter();
        $repository = new FightOpponentRepository(self::uuidGenerator(), self::entityManager());

        self::loadFixtures($initiator, $otherWithDistantRank, $otherWithCloseRank);

        $opponent = $repository->createFromExternalDetailsOfAnAvailableOpponent($initiator->characterId());

        self::assertFightOpponentRepresentsTheCharacter($otherWithCloseRank, $opponent);
    }

    /**
     * @throws Exception|NoOpponentAvailableException
     */
    public function testItPicksTheCharacterWhoFoughtTheInitiatorTheLeast(): void
    {
        $otherWhoFoughtInitiatorTwice = $this->otherCharacter('c421cd88-92cd-4ff3-8718-279f3ef1815b');
        $otherWhoFoughtInitiatorOnce  = $this->otherCharacter('37ae2768-6ca4-42a7-9998-3249bc178e40');

        $initiator  = CharacterProvider::freshCharacter();
        $repository = new FightOpponentRepository(self::uuidGenerator(), self::entityManager());

        self::loadFixtures($initiator, $otherWhoFoughtInitiatorTwice, $otherWhoFoughtInitiatorOnce);
        self::loadFixtures(...$this->fights($initiator, $otherWhoFoughtInitiatorTwice, $otherWhoFoughtInitiatorOnce));

        $opponent = $repository->createFromExternalDetailsOfAnAvailableOpponent($initiator->characterId());

        self::assertFightOpponentRepresentsTheCharacter($otherWhoFoughtInitiatorOnce, $opponent);
    }

    public static function assertFightOpponentRepresentsTheCharacter(Character $expected, FightOpponent $actual): void
    {
        self::assertThat($actual, new FightParticipantRepresentsTheCharacterConstraint($expected));
    }

    private function otherCharacter(string $uuid): Character
    {
        try {
            return CharacterProvider::customCharacter([
                'characterId' => new CharacterId($uuid),
            ]);
        } catch (ReflectionException $e) {
            self::fail($e->getMessage());
        }
    }

    private function otherCharacterWithACloseRank(): Character
    {
        try {
            return CharacterProvider::customCharacter([
                'characterId'   => new CharacterId('3eda1c23-e562-4e75-be34-53557656902f'),
                'characterRank' => new CharacterRank(13),
            ]);
        } catch (ReflectionException $e) {
            self::fail($e->getMessage());
        }
    }

    private function otherCharacterWithADistantRank(): Character
    {
        try {
            return CharacterProvider::customCharacter([
                'characterId'   => new CharacterId('4ee1dccc-96dc-42e3-9330-d4b07002cf0c'),
                'characterRank' => new CharacterRank(20),
            ]);
        } catch (ReflectionException $e) {
            self::fail($e->getMessage());
        }
    }

    /**
     * @return iterable<Fight>
     */
    private function fights(Character $initiator, Character $otherWhoFoughtTwice, Character $otherWhoFoughtOnce): iterable
    {
        yield Fight::initiate(
            new FightId('8e746417-ed2a-4284-b510-3e27a8e6b5cb'),
            self::fightInitiator($initiator),
            self::fightOpponent($otherWhoFoughtTwice),
        );

        yield Fight::initiate(
            new FightId('92be6c4e-9cf2-485f-847a-48c28c6e47f2'),
            self::fightInitiator($initiator),
            self::fightOpponent($otherWhoFoughtTwice),
        );

        yield Fight::initiate(
            new FightId('359c1a9a-e7eb-4b43-92a6-b7164e063ea4'),
            self::fightInitiator($initiator),
            self::fightOpponent($otherWhoFoughtOnce),
        );
    }

    private static function fightInitiator(Character $character): FightInitiator
    {
        return FightInitiator::create(
            $character->characterId(),
            new FightParticipantId(self::uuidGenerator()->uuid4()),
            new FightParticipantHealth($character->characterHealth()->value()),
            new FightParticipantAttack($character->characterAttack()->value()),
            new FightParticipantDefense($character->characterDefense()->value()),
            new FightParticipantMagik($character->characterMagik()->value()),
            new FightParticipantRank($character->characterRank()->value()),
        );
    }

    private static function fightOpponent(Character $character): FightOpponent
    {
        return FightOpponent::create(
            $character->characterId(),
            new FightParticipantId(self::uuidGenerator()->uuid4()),
            new FightParticipantHealth($character->characterHealth()->value()),
            new FightParticipantAttack($character->characterAttack()->value()),
            new FightParticipantDefense($character->characterDefense()->value()),
            new FightParticipantMagik($character->characterMagik()->value()),
            new FightParticipantRank($character->characterRank()->value()),
        );
    }

    private static function uuidGenerator(): UuidGenerator
    {
        if (null === self::$uuidGenerator) {
            self::$uuidGenerator = new UuidGeneratorUsingRamsey();
        }

        return self::$uuidGenerator;
    }
}
