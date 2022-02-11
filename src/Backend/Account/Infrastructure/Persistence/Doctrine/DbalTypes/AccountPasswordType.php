<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes\AbstractStringType;

final class AccountPasswordType extends AbstractStringType
{
    protected function mappedClass(): string
    {
        return AccountPassword::class;
    }
}
