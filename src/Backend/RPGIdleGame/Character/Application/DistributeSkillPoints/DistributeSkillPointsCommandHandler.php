<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DistributeSkillPoints;

use Kishlin\Backend\RPGIdleGame\Character\Domain\CharacterGateway;
use Kishlin\Backend\RPGIdleGame\Character\Domain\NotEnoughSkillPointsException;
use Kishlin\Backend\RPGIdleGame\Character\Domain\PointsCanOnlyBeIncreasedException;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Exception\InvalidValueException;

final class DistributeSkillPointsCommandHandler implements CommandHandler
{
    public function __construct(
        private CharacterGateway $characterGateway,
    ) {
    }

    /**
     * @throws CharacterNotFoundException|InvalidValueException|NotEnoughSkillPointsException|PointsCanOnlyBeIncreasedException
     */
    public function __invoke(DistributeSkillPointsCommand $command): void
    {
        $character = $this->characterGateway->findOneById($command->characterId());

        if (null === $character) {
            throw new CharacterNotFoundException();
        }

        $character->increaseHealthBy($command->healthPointsToAdd());

        $character->increaseAttackBy($command->attackPointsToAdd());

        $character->increaseDefenseBy($command->defensePointsToAdd());

        $character->increaseMagikBy($command->magikPointsToAdd());

        $this->characterGateway->save($character);
    }
}
