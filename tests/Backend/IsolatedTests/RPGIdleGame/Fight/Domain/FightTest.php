<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\AbstractFightParticipant;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Dice;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightParticipantHadADrawDomainEvent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightParticipantHadALossDomainEvent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightParticipantHadAWinDomainEvent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\ValueObject\FightParticipantCharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\RandomDice;
use Kishlin\Backend\Shared\Domain\Bus\Event\DomainEvent;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Kishlin\Backend\Shared\Infrastructure\Randomness\UuidGeneratorUsingRamsey;
use Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Constraint\FightIsADrawResultConstraint;
use Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Constraint\FightRecordedAWinnerResultConstraint;
use Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Constraint\FightWasWonByResultConstraint;
use Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Exporter\FightExporter;
use Kishlin\Tests\Backend\Tools\Provider\FightProvider;
use Kishlin\Tests\Backend\Tools\Test\Isolated\Constraint\AggregateRecordedDomainEventsConstraint;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\FightTurn
 */
final class FightTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $fight = FightProvider::initiatedFight();

        self::assertIsIterable($fight->turns());
        self::assertEmpty($fight->turns());

        self::assertInstanceOf(FightOpponent::class, $fight->opponent());
        self::assertInstanceOf(FightInitiator::class, $fight->initiator());

        self::assertNull($fight->winnerId()->value());
    }

    public function testItIsADrawWhenParticipantsCannotDamageEachOther(): void
    {
        $fight = FightProvider::fightWhereParticipantsCannotDamageEachOther();

        $fight->unfold(
            $this->getMockForAbstractClass(Dice::class),
            $this->getMockForAbstractClass(UuidGenerator::class),
        );

        self::assertFightIsADraw($fight);

        self::assertFightRecordedDomainEvents(
            $fight,
            new FightParticipantHadADrawDomainEvent($fight->id(), $fight->initiator()->characterId()),
            new FightParticipantHadADrawDomainEvent($fight->id(), $fight->opponent()->characterId()),
        );
    }

    /**
     * @dataProvider \Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\FightTest::fightWithOnlyOnePossibleWinnerProvider()
     */
    public function testItDetectsWhenThereIsOnlyOnePossibleWinner(
        Fight $fight,
        AbstractFightParticipant $expectedWinner,
        AbstractFightParticipant $expectedLoser
    ): void {
        $fight->unfold(
            $this->getMockForAbstractClass(Dice::class),
            $this->getMockForAbstractClass(UuidGenerator::class),
        );

        self::assertFightWasWonBy($fight, $expectedWinner);

        self::assertFightRecordedDomainEvents(
            $fight,
            new FightParticipantHadAWinDomainEvent($fight->id(), $expectedWinner->characterId()),
            new FightParticipantHadALossDomainEvent($fight->id(), $expectedLoser->characterId(), $fight->date()),
        );
    }

    public function testItCanUnfoldUntilThereIsAClearWinner(): void
    {
        $fight = FightProvider::initiatedFight();

        $fight->unfold(new RandomDice(), new UuidGeneratorUsingRamsey());

        self::assertFightRecordedAWinner($fight);

        $loser = $fight->winnerId()->equals($fight->initiator()->characterId()) ? $fight->opponent() : $fight->initiator();

        self::assertFightRecordedDomainEvents(
            $fight,
            new FightParticipantHadAWinDomainEvent($fight->id(), FightParticipantCharacterId::fromOther($fight->winnerId())),
            new FightParticipantHadALossDomainEvent($fight->id(), $loser->characterId(), $fight->date()),
        );
    }

    public static function assertFightIsADraw(Fight $fight): void
    {
        self::assertThat($fight, new FightIsADrawResultConstraint());
    }

    public static function assertFightRecordedAWinner(Fight $fight): void
    {
        self::assertThat($fight, new FightRecordedAWinnerResultConstraint());
    }

    public static function assertFightWasWonBy(Fight $fight, AbstractFightParticipant $winner): void
    {
        self::assertThat($fight, new FightWasWonByResultConstraint($winner));
    }

    /**
     * @noinspection PhpDocSignatureInspection
     *
     * @return iterable<array<int, AbstractFightParticipant|Fight>>
     */
    public function fightWithOnlyOnePossibleWinnerProvider(): iterable
    {
        $fight = FightProvider::fightWhereOnlyInitiatorCanDealDamages();

        yield [$fight, $fight->initiator(), $fight->opponent()];

        $fight = FightProvider::fightWhereOnlyOpponentCanDealDamages();

        yield [$fight, $fight->opponent(), $fight->initiator()];
    }

    public static function assertFightRecordedDomainEvents(Fight $fight, DomainEvent ...$events): void
    {
        self::assertThat($fight, new AggregateRecordedDomainEventsConstraint($events, FightExporter::class));
    }
}
