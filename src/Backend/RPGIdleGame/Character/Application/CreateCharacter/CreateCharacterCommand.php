<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Character\Application\CreateCharacter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterId;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterName;
use Kishlin\Backend\RPGIdleGame\Character\Domain\ValueObject\CharacterOwner;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Message\Mapping;

final class CreateCharacterCommand implements Command
{
    use Mapping;

    private function __construct(
        private string $characterId,
        private string $characterName,
        private string $ownerUuid,
    ) {
    }

    public function characterId(): CharacterId
    {
        return new CharacterId($this->characterId);
    }

    public function characterName(): CharacterName
    {
        return new CharacterName($this->characterName);
    }

    public function characterOwner(): CharacterOwner
    {
        return new CharacterOwner($this->ownerUuid);
    }

    public static function fromScalars(string $characterId, string $characterName, string $ownerUuid): self
    {
        return new self($characterId, $characterName, $ownerUuid);
    }

    /**
     * @param array{characterId: string, characterName: string, ownerUuid: string} $request
     */
    public static function fromRequest(array $request): self
    {
        return new self(
            self::getString($request, 'characterId'),
            self::getString($request, 'characterName'),
            self::getString($request, 'ownerUuid'),
        );
    }
}
