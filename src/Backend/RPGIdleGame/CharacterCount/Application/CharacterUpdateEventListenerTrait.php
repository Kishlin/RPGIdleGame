<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\CharacterCount\Application;

use Kishlin\Backend\RPGIdleGame\CharacterCount\Application\OnCharacterCreated\CharacterCountNotFoundException;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCountGateway;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\ValueObject\CharacterCountOwner;
use Kishlin\Backend\Shared\Domain\ValueObject\UuidValueObject;

trait CharacterUpdateEventListenerTrait
{
    public function __construct(
        protected CharacterCountGateway $characterCountGateway,
    ) {
    }

    /**
     * @throws CharacterCountNotFoundException
     */
    protected function findCharacterCount(UuidValueObject $characterOwner): CharacterCount
    {
        $characterCountOwner = CharacterCountOwner::fromOther($characterOwner);
        $characterCount      = $this->characterCountGateway->findForOwner($characterCountOwner);

        if (null === $characterCount) {
            throw new CharacterCountNotFoundException();
        }

        return $characterCount;
    }
}
