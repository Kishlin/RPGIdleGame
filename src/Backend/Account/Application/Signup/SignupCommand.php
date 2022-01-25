<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Signup;

use Kishlin\Backend\Account\Domain\ValueObject\AccountEmail;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountPassword;
use Kishlin\Backend\Account\Domain\ValueObject\AccountUsername;
use Kishlin\Backend\Shared\Domain\Bus\Command\Command;
use Kishlin\Backend\Shared\Domain\Bus\Message\Mapping;

final class SignupCommand implements Command
{
    use Mapping;

    private function __construct(
        private string $id,
        private string $username,
        private string $email,
        private string $password,
    ) {
    }

    public function id(): AccountId
    {
        return new AccountId($this->id);
    }

    public function username(): AccountUsername
    {
        return new AccountUsername($this->username);
    }

    public function email(): AccountEmail
    {
        return new AccountEmail($this->email);
    }

    public function password(): AccountPassword
    {
        return new AccountPassword($this->password);
    }

    public static function fromScalars(string $id, string $username, string $password, string $email): self
    {
        return new self($id, $username, $email, $password);
    }
}
