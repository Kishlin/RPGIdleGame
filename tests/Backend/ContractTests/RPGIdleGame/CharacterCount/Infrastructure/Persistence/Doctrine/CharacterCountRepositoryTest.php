<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine\CharacterCountRepository;
use Kishlin\Tests\Backend\Tools\Provider\CharacterCountProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\CharacterCount\Infrastructure\Persistence\Doctrine\CharacterCountRepository
 */
final class CharacterCountRepositoryTest extends RepositoryContractTestCase
{
    public function testItCanSaveACharacterCount(): void
    {
        $characterCount = CharacterCountProvider::freshCount();

        $repository = new CharacterCountRepository(self::entityManager());

        $repository->save($characterCount);

        self::assertCount(1, self::entityManager()->getRepository(CharacterCount::class)->findAll());
    }

    /**
     * @throws Exception
     */
    public function testItCanSaveAndUpdatedCharacterCount(): void
    {
        $characterCount = CharacterCountProvider::freshCount();
        self::loadFixtures($characterCount);

        $repository = new CharacterCountRepository(self::entityManager());

        $savedCharacterCount = $repository->findForOwner($characterCount->owner());

        self::assertNotNull($savedCharacterCount);

        $savedCharacterCount->incrementOnCharacterCreation();

        $repository->save($savedCharacterCount);

        self::assertEquals(1, self::entityManager()->getConnection()->fetchOne(
            'SELECT 1 FROM character_counts WHERE owner_id = :owner LIMIT 1',
            ['owner' => $characterCount->owner()->value()],
        ));
    }
}
