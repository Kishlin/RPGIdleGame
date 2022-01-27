<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\ContractTests\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Exception;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\CharacterNotFoundException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\View\CompleteCharacterView;
use Kishlin\Backend\RPGIdleGame\Character\Infrastructure\Persistence\Doctrine\CharacterViewerUsingDoctrine;
use Kishlin\Tests\Backend\Tools\Provider\CharacterProvider;
use Kishlin\Tests\Backend\Tools\Test\Contract\RepositoryContractTestCase;
use ReflectionException;

/**
 * @internal
 * @coversNothing
 */
final class CharacterViewerTest extends RepositoryContractTestCase
{
    /**
     * @throws CharacterNotFoundException|Exception|ReflectionException
     */
    public function testItCanViewACharacter(): void
    {
        $character = CharacterProvider::completeCharacter();
        self::loadFixtures($character);

        $repository = new CharacterViewerUsingDoctrine(self::entityManager());

        $view = $repository->viewOneById($character->characterId()->value());

        self::assertInstanceOf(CompleteCharacterView::class, $view);
    }
}
