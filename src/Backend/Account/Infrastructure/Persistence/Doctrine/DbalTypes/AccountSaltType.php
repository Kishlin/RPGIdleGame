<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Account\Domain\ValueObject\AccountSalt;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractStringType;

final class AccountSaltType extends AbstractStringType
{
    protected function mappedClass(): string
    {
        return AccountSalt::class;
    }
}
