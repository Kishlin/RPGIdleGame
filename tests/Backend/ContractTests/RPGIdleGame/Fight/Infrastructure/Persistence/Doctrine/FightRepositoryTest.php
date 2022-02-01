<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Provider\FightProvider;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightRepository
 */
final class FightRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testItCanSaveAndRetrieveAFight(): void
    {
        $fight      = FightProvider::fightWithThreeTurns();
        $repository = new FightRepository(self::entityManager());

        self::loadFixtures($this->initiator($fight), $this->opponent($fight));

        $repository->save($fight);

        /** @var Fight $savedFight */
        $savedFight = $repository->findOneById($fight->id());

        self::assertSame($savedFight, $fight);
        self::assertNull($savedFight->winnerId()->value());
        self::assertInstanceOf(FightOpponent::class, $savedFight->opponent());
        self::assertInstanceOf(FightInitiator::class, $savedFight->initiator());
        self::assertCount(3, $savedFight->turns());
    }

    private function initiator(Fight $fight): Character
    {
        try {
            $character = CharacterProvider::freshCharacter();
            $uuid      = ReflectionHelper::propertyValue($fight->initiator(), 'characterId');

            ReflectionHelper::writePropertyValue($character, 'characterId', CharacterId::fromOther($uuid));

            return $character;
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }
    }

    private function opponent(Fight $fight): Character
    {
        try {
            $character = CharacterProvider::freshCharacter();
            $uuid      = ReflectionHelper::propertyValue($fight->opponent(), 'characterId');

            ReflectionHelper::writePropertyValue($character, 'characterId', CharacterId::fromOther($uuid));

            return $character;
        } catch (ReflectionException $e) {
            $this->fail($e->getMessage());
        }
    }
}
