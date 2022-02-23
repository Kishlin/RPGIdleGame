<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Application\Signup;

use Kishlin\Backend\Account\Domain\Account;
use Kishlin\Backend\Account\Domain\AccountGateway;
use Kishlin\Backend\Account\Domain\SaltGenerator;
use Kishlin\Backend\Account\Domain\ValueObject\AccountId;
use Kishlin\Backend\Account\Domain\ValueObject\AccountSalt;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandHandler;
use Kishlin\Backend\Shared\Domain\Bus\Event\EventDispatcher;

final class SignupCommandHandler implements CommandHandler
{
    public function __construct(
        private AccountWithUsernameGateway $accountWithUsernameGateway,
        private AccountWithEmailGateway $accountWithEmailGateway,
        private AccountGateway $accountGateway,
        private SaltGenerator $saltGenerator,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    /**
     * @throws AnAccountAlreadyExistsException
     */
    public function __invoke(SignupCommand $command): AccountId
    {
        if ($this->accountWithUsernameGateway->thereAlreadyIsAnAccountWithUsername($command->username())) {
            throw new AnAccountAlreadyExistsException('username');
        }

        if ($this->accountWithEmailGateway->thereAlreadyIsAnAccountWithEmail($command->email())) {
            throw new AnAccountAlreadyExistsException('email');
        }

        $salt = new AccountSalt($this->saltGenerator->salt());

        $account = Account::createActiveAccount(
            $command->id(),
            $command->username(),
            $command->password(),
            $command->email(),
            $salt,
        );

        $this->accountGateway->save($account);

        $this->eventDispatcher->dispatch(...$account->pullDomainEvents());

        return $account->id();
    }
}
