<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\CannotAccessFightsException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightNotFoundException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightViewRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightViewRepository
 */
final class FightViewRepositoryTest extends RepositoryContractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        self::execute(self::completeFixturesQueries());
    }

    /**
     * @throws Exception
     */
    public function testItCanViewAFight(): void
    {
        $repository = new FightViewRepository(self::entityManager());

        $view = $repository->viewOneById('fight-2', 'account-0');

        self::assertInstanceOf(JsonableFightView::class, $view);
    }

    /**
     * @throws Exception
     */
    public function testItFailsToViewAFightItWasNotAPartOf(): void
    {
        $repository = new FightViewRepository(self::entityManager());

        self::expectException(FightNotFoundException::class);
        $repository->viewOneById('fight-0', 'account-2');
    }

    /**
     * @throws Exception
     */
    public function testItFailsToViewAFightThatDoesNotExist(): void
    {
        $repository = new FightViewRepository(self::entityManager());

        self::expectException(FightNotFoundException::class);
        $repository->viewOneById('fight-4', 'account-0');
    }

    /**
     * @throws Exception
     */
    public function testItCanViewAllFightsForAFighter(): void
    {
        $repository = new FightViewRepository(self::entityManager());

        self::assertCount(4, $repository->viewAllForFighter('character-0', 'account-0')->toArray());
        self::assertCount(1, $repository->viewAllForFighter('character-3', 'account-1')->toArray());
        self::assertCount(0, $repository->viewAllForFighter('character-4', 'account-1')->toArray());
    }

    /**
     * @throws Exception
     */
    public function testItFailsToViewAllFightsForAFighterItHasNotAccessTo(): void
    {
        $repository = new FightViewRepository(self::entityManager());

        self::expectException(CannotAccessFightsException::class);
        $repository->viewAllForFighter('character-0', 'account-1');
    }

    private static function completeFixturesQueries(): string
    {
        return <<<'SQL'
INSERT INTO accounts (id, username, email, password, salt, is_active) VALUES
    ('account-0', 'User', 'user@example.com', 'password', 'salt-0', true),
    ('account-1', 'Stranger', 'stranger@example.com', 'password', 'salt-1', true)
;

INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, is_active, fights_count, wins_count, draws_count, losses_count) VALUES
    ('character-0', 'account-0', 'Kishlin', 12, 25, 18, 12, 10, 25, true, 4, 1, 1, 2),
    ('character-1', 'account-1', 'Brawler', 8, 20, 16, 8, 12, 20, true, 2, 1, 0, 1),
    ('character-2', 'account-1', 'Fighter', 15, 30, 25, 5, 10, 32, true, 1, 1, 0, 0),
    ('character-3', 'account-1', 'OnlyDraws', 5, 23, 1, 40, 1, 24, true, 1, 0, 1, 0),
    ('character-4', 'account-1', 'NoFights', 12, 10, 0, 0, 0, 0, true, 0, 0, 0, 0)
;

INSERT INTO fight_initiators (id, character_id, health, attack, defense, magik, rank) VALUES
    ('initiator-0', 'character-0', 25, 18, 12, 10, 26),
    ('initiator-1', 'character-1', 20, 16, 8, 12, 19),
    ('initiator-2', 'character-2', 30, 25, 5, 10, 31),
    ('initiator-3', 'character-0', 25, 18, 12, 10, 25)
;

INSERT INTO fight_opponents (id, character_id, health, attack, defense, magik, rank) VALUES
    ('opponent-0', 'character-1', 20, 16, 8, 12, 20),
    ('opponent-1', 'character-0', 25, 18, 12, 10, 27),
    ('opponent-2', 'character-0', 25, 18, 12, 10, 26),
    ('opponent-3', 'character-3', 23, 1, 40, 1, 24)
;

INSERT INTO fights (id, initiator, opponent, winner_id) VALUES
    ('fight-0', 'initiator-0', 'opponent-0', 'character-0'),
    ('fight-1', 'initiator-1', 'opponent-1', 'character-1'),
    ('fight-2', 'initiator-2', 'opponent-2', 'character-2'),
    ('fight-3', 'initiator-3', 'opponent-3', null)
;

INSERT INTO fight_turns (id, fight_id, attacker_id, index, attacker_attack, attacker_magik, attacker_dice_roll, defender_defense, damage_dealt, defender_health) VALUES
    ('turn-0-0', 'fight-0', 'initiator-0', 0, 18, 10, 15, 8, 7, 13),
    ('turn-0-1', 'fight-0', 'opponent-0', 1, 16, 12, 13, 12, 1, 24),
    ('turn-0-2', 'fight-0', 'initiator-0', 2, 18, 10, 10, 8, 12, 1),
    ('turn-0-3', 'fight-0', 'opponent-0', 3, 16, 12, 8, 12, 0, 22),
    ('turn-0-4', 'fight-0', 'initiator-0', 4, 18, 10, 11, 8, 3, 0),
    ('turn-1-0', 'fight-1', 'initiator-1', 0, 25, 10, 24, 12, 12, 13),
    ('turn-1-1', 'fight-1', 'opponent-1', 1, 18, 10, 5, 5, 0, 30),
    ('turn-1-2', 'fight-1', 'initiator-1', 2, 25, 10, 25, 12, 13, 0),
    ('turn-2-0', 'fight-2', 'initiator-1', 0, 16, 12, 16, 12, 4, 21),
    ('turn-2-1', 'fight-2', 'opponent-1', 1, 18, 10, 2, 8, 0, 20),
    ('turn-2-2', 'fight-2', 'initiator-1', 2, 16, 12, 12, 12, 12, 9),
    ('turn-2-3', 'fight-2', 'opponent-1', 3, 18, 10, 16, 8, 8, 12),
    ('turn-2-4', 'fight-2', 'initiator-1', 4, 16, 12, 15, 12, 3, 6),
    ('turn-2-5', 'fight-2', 'opponent-1', 5, 18, 10, 7, 8, 0, 12),
    ('turn-2-6', 'fight-2', 'initiator-1', 6, 16, 12, 12, 12, 12, 0)
;
SQL;
    }
}
