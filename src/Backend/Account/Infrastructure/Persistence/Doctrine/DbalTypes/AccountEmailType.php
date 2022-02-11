<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractStringType;

final class AccountEmailType extends AbstractStringType
{
    protected function mappedClass(): string
    {
        return AccountEmail::class;
    }
}
