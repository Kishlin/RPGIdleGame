<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreationLimitCheckerDoesNotExistException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\CreationAllowanceRepository;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\CreationAllowanceRepository
 */
final class CreationAllowanceRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws CreationLimitCheckerDoesNotExistException|Exception
     */
    public function testItTellsAnOwnerHasReachedLimit(): void
    {
        $ownerUuid = 'bc13859b-05ae-43fb-b41f-a321c3dce96b';

        self::loadCharacterCountFixture($ownerUuid, 5, false);

        $repository = new CreationAllowanceRepository(self::entityManager());

        self::assertFalse($repository->ownerHasReachedCharacterLimit(new CharacterOwner($ownerUuid)));
    }

    /**
     * @throws CreationLimitCheckerDoesNotExistException|Exception
     */
    public function testItTellsAnOwnerWhoReachedLimitCannotCreateACharacter(): void
    {
        $ownerUuid = 'de79744e-d4c0-446e-9897-4b5614f52803';

        self::loadCharacterCountFixture($ownerUuid, 10, true);

        $repository = new CreationAllowanceRepository(self::entityManager());

        self::assertTrue($repository->ownerHasReachedCharacterLimit(new CharacterOwner($ownerUuid)));
    }

    /**
     * @throws CreationLimitCheckerDoesNotExistException|Exception
     */
    public function testItReturnsThrowsAnExceptionIfRecordIsMissing(): void
    {
        $uuidWithoutMatchingOwner = 'bab0e158-b148-4a3f-800a-655028da4934';

        $repository = new CreationAllowanceRepository(self::entityManager());

        self::expectException(CreationLimitCheckerDoesNotExistException::class);
        $repository->ownerHasReachedCharacterLimit(new CharacterOwner($uuidWithoutMatchingOwner));
    }

    /**
     * @throws Exception
     */
    public function loadCharacterCountFixture(string $ownerUuid, int $count, bool $hasReachedLimit): void
    {
        $query = <<<'SQL'
INSERT INTO character_counts (owner_id, character_count, reached_limit)
VALUES (:owner, :count, :hasReachedLimit);
SQL;

        $params = ['owner' => $ownerUuid, 'count' => $count, 'hasReachedLimit' => var_export($hasReachedLimit, true)];

        self::entityManager()->getConnection()->executeQuery($query, $params);
    }
}
