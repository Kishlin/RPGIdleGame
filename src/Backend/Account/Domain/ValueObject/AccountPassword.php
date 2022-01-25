<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain\ValueObject;

use Kishlin\Backend\Shared\Domain\ValueObject\StringValueObject;

final class AccountPassword extends StringValueObject
{
    public function __construct(string $value)
    {
        $hashed = password_hash($value, PASSWORD_ARGON2I);

        parent::__construct($hashed);
    }
}
