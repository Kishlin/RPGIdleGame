<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommandHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeleteCharacterCommandHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\DistributeSkillPointsCommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

trait CharacterServicesTrait
{
    private ?CharacterGatewaySpy $characterGatewaySpy = null;

    private ?CreationAllowanceGatewaySpy $creationAllowanceGatewaySpy = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function characterCountGatewaySpy(): object;

    public function characterGatewaySpy(): CharacterGatewaySpy
    {
        if (null === $this->characterGatewaySpy) {
            $this->characterGatewaySpy = new CharacterGatewaySpy();
        }

        return $this->characterGatewaySpy;
    }

    public function creationAllowanceGatewaySpy(): CreationAllowanceGatewaySpy
    {
        if (null === $this->creationAllowanceGatewaySpy) {
            $this->creationAllowanceGatewaySpy = new CreationAllowanceGatewaySpy($this->characterCountGatewaySpy());
        }

        return $this->creationAllowanceGatewaySpy;
    }

    public function distributeSkillPointsHandler(): DistributeSkillPointsCommandHandler
    {
        return new DistributeSkillPointsCommandHandler($this->characterGatewaySpy());
    }

    public function createCharacterHandler(): CreateCharacterCommandHandler
    {
        return new CreateCharacterCommandHandler(
            $this->creationAllowanceGatewaySpy(),
            $this->characterGatewaySpy(),
            $this->eventDispatcher(),
        );
    }

    public function deleteCharacterHandler(): DeleteCharacterCommandHandler
    {
        $gatewaySpy = $this->characterGatewaySpy();

        return new DeleteCharacterCommandHandler(
            $gatewaySpy,
            $gatewaySpy,
            $this->eventDispatcher(),
        );
    }
}
