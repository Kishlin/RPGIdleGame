<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\CharacterStats\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\CharacterStats;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Domain\ValueObject\CharacterStatsCharacterId;
use Kishlin\Backend\RPGIdleGame\CharacterStats\Infrastructure\Persistence\Doctrine\CharacterStatsRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterStats\Infrastructure\Persistence\Doctrine\CharacterStatsRepository
 */
final class CharacterStatsRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItCanSaveAndRetrieveStats(): void
    {
        $character = CharacterProvider::freshCharacter();

        self::loadFixtures($character);

        $characterStatsId = CharacterStatsCharacterId::fromOther($character->id());
        $characterStats   = CharacterStats::fromScalars($characterStatsId, 15, 3, 8);

        $repository = new CharacterStatsRepository(self::entityManager());

        $repository->save($characterStats);

        $savedStats = $repository->findForCharacter($characterStatsId);

        self::assertNotNull($savedStats);

        self::assertTrue($characterStats->fightsCount()->equals($savedStats->fightsCount()));
        self::assertTrue($characterStats->winsCount()->equals($savedStats->winsCount()));
        self::assertTrue($characterStats->drawsCount()->equals($savedStats->drawsCount()));
        self::assertTrue($characterStats->lossesCount()->equals($savedStats->lossesCount()));
    }
}
