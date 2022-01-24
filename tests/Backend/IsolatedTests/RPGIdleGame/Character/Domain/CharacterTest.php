<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Domain;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterCreatedDomainEvent;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Tests\Backend\Tools\Test\Isolated\AggregateRootIsolatedTestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Character\Domain\Character
 */
final class CharacterTest extends AggregateRootIsolatedTestCase
{
    public function testItCanBeCreated(): void
    {
        $characterId    = new CharacterId('413893f0-a041-430a-95c0-70f10aff8de6');
        $characterOwner = new CharacterOwner('d71ddce2-ca04-4066-abb0-189f481b5ac9');
        $characterName  = new CharacterName('Kishlin');

        $character = Character::createFresh($characterId, $characterName, $characterOwner);

        self::assertItRecordedDomainEvents(
            $character,
            new CharacterCreatedDomainEvent($characterId, $characterOwner),
        );
    }
}
