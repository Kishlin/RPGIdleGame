<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FighterId;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\FightRequesterId;
use Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightInitiationAllowanceRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Infrastructure\Persistence\Doctrine\FightInitiationAllowanceRepository
 */
final class FightInitiationAllowanceRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItAuthorizeTheFightForTheRightfulOwner(): void
    {
        $character = CharacterProvider::freshCharacter();

        $this->loadFixtures($character);

        $repository = new FightInitiationAllowanceRepository(self::entityManager());

        self::assertTrue(
            $repository->requesterIsAllowedToFightWithFighter(
                requesterId: FightRequesterId::fromOther($character->owner()),
                fighterId: FighterId::fromOther($character->id()),
            )
        );
    }

    /**
     * @throws Exception
     */
    public function testItProhibitsTheFightToAStranger(): void
    {
        $character = CharacterProvider::freshCharacter();
        $stranger  = new CharacterOwner('35618629-b91a-4619-9476-550a09108e44');

        $this->loadFixtures($character);

        $repository = new FightInitiationAllowanceRepository(self::entityManager());

        self::assertFalse(
            $repository->requesterIsAllowedToFightWithFighter(
                requesterId: FightRequesterId::fromOther($stranger),
                fighterId: FighterId::fromOther($character->id()),
            )
        );
    }
}
