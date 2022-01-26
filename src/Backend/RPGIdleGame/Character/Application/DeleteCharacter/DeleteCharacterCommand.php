<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\DeleteCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;

final class DeleteCharacterCommand implements Command
{
    private function __construct(
        private string $characterId,
        private string $accountRequestingDeletion,
    ) {
    }

    public function characterId(): CharacterId
    {
        return new CharacterId($this->characterId);
    }

    public function accountRequestingDeletion(): CharacterOwner
    {
        return new CharacterOwner($this->accountRequestingDeletion);
    }

    public static function fromScalars(string $characterId, string $accountRequestingDeletionUuid): self
    {
        return new self($characterId, $accountRequestingDeletionUuid);
    }
}
