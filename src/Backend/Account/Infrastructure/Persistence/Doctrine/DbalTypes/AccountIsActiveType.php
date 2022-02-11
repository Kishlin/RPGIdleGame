<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Account\Domain\ValueObject\AccountIsActive;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractBooleanType;

final class AccountIsActiveType extends AbstractBooleanType
{
    protected function mappedClass(): string
    {
        return AccountIsActive::class;
    }
}
