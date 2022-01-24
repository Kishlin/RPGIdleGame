<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractUuidType;

final class AccountIdType extends AbstractUuidType
{
    protected function mappedClass(): string
    {
        return AccountId::class;
    }
}
