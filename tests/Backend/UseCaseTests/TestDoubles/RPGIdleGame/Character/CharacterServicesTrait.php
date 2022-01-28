<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character;

use Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter\CreateCharacterCommandHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter\DeleteCharacterCommandHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints\DistributeSkillPointsCommandHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewAllCharacter\ViewAllCharactersQueryHandler;
use Kishlin\Backend\RPGIdleGame\Character\Application\ViewCharacter\ViewCharacterQueryHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

trait CharacterServicesTrait
{
    private ?CharacterGatewaySpy $characterGatewaySpy = null;

    private ?DistributeSkillPointsCommandHandler $distributeSkillPointsCommandHandler = null;

    private ?CreateCharacterCommandHandler $createCharacterCommandHandler = null;

    private ?DeleteCharacterCommandHandler $deleteCharacterCommandHandler = null;

    private ?ViewCharacterQueryHandler $viewCharacterQueryHandler = null;

    private ?ViewAllCharactersQueryHandler $viewAllCharactersQueryHandler = null;

    abstract public function eventDispatcher(): EventDispatcher;

    abstract public function characterCountGatewaySpy(): object;

    public function characterGatewaySpy(): CharacterGatewaySpy
    {
        if (null === $this->characterGatewaySpy) {
            $this->characterGatewaySpy = new CharacterGatewaySpy();
        }

        return $this->characterGatewaySpy;
    }

    public function distributeSkillPointsHandler(): DistributeSkillPointsCommandHandler
    {
        if (null === $this->distributeSkillPointsCommandHandler) {
            $this->distributeSkillPointsCommandHandler = new DistributeSkillPointsCommandHandler(
                $this->characterGatewaySpy()
            );
        }

        return $this->distributeSkillPointsCommandHandler;
    }

    public function createCharacterHandler(): CreateCharacterCommandHandler
    {
        if (null === $this->createCharacterCommandHandler) {
            $this->createCharacterCommandHandler = new CreateCharacterCommandHandler(
                $this->characterCountGatewaySpy(),
                $this->characterGatewaySpy(),
                $this->eventDispatcher(),
            );
        }

        return $this->createCharacterCommandHandler;
    }

    public function deleteCharacterHandler(): DeleteCharacterCommandHandler
    {
        if (null === $this->deleteCharacterCommandHandler) {
            $this->deleteCharacterCommandHandler = new DeleteCharacterCommandHandler(
                $this->characterGatewaySpy(),
                $this->characterGatewaySpy(),
                $this->eventDispatcher()
            );
        }

        return $this->deleteCharacterCommandHandler;
    }

    public function viewCharacterQueryHandler(): ViewCharacterQueryHandler
    {
        if (null === $this->viewCharacterQueryHandler) {
            $this->viewCharacterQueryHandler = new ViewCharacterQueryHandler($this->characterGatewaySpy());
        }

        return $this->viewCharacterQueryHandler;
    }

    public function viewAllCharactersQueryHandler(): ViewAllCharactersQueryHandler
    {
        if (null === $this->viewAllCharactersQueryHandler) {
            $this->viewAllCharactersQueryHandler = new ViewAllCharactersQueryHandler($this->characterGatewaySpy());
        }

        return $this->viewAllCharactersQueryHandler;
    }
}
