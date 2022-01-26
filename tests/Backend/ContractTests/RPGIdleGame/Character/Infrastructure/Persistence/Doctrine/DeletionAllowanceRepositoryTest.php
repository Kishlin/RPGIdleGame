<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DeletionAllowanceRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\DeletionAllowanceRepository
 */
final class DeletionAllowanceRepositoryTest extends RepositoryContractTestCase
{
    /**
     * @throws Exception
     */
    public function testItAuthorizeDeletionForTheRightfulOwner(): void
    {
        $character = CharacterProvider::freshCharacter();

        $this->loadFixtures($character);

        $repository = new DeletionAllowanceRepository(self::entityManager());

        self::assertTrue(
            $repository->requesterIsTheRightfulOwner($character->characterOwner(), $character->characterId())
        );
    }

    /**
     * @throws Exception
     */
    public function testItProhibitsDeletionToASStranger(): void
    {
        $character = CharacterProvider::freshCharacter();
        $stranger  = new CharacterOwner('35618629-b91a-4619-9476-550a09108e44');

        $this->loadFixtures($character);

        $repository = new DeletionAllowanceRepository(self::entityManager());

        self::assertFalse(
            $repository->requesterIsTheRightfulOwner($stranger, $character->characterId())
        );
    }
}
