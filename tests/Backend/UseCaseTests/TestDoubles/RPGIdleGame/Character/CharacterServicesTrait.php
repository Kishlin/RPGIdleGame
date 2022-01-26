<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

trait CharacterServicesTrait
{
    private ?CharacterGatewaySpy $characterGatewaySpy = null;

    abstract public function eventDispatcher(): EventDispatcher;

    public function characterGatewaySpy(): CharacterGatewaySpy
    {
        if (null === $this->characterGatewaySpy) {
            $this->characterGatewaySpy = new CharacterGatewaySpy();
        }

        return $this->characterGatewaySpy;
    }

    public function createCharacterHandler(): CreateCharacterCommandHandler
    {
        return new CreateCharacterCommandHandler(
            $this->characterCountGatewaySpy(),
            $this->characterGatewaySpy(),
            $this->eventDispatcher(),
        );
    }
}
