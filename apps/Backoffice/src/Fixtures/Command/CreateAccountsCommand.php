<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Fixtures\Command;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\Account\Domain\SaltGenerator;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class CreateAccountsCommand extends Command
{
    private const NAME = 'kishlin:backoffice:fixtures:accounts';

    private const OPT_ACCOUNTS = 'accounts';

    private const ACCOUNT_QUERY    = 'INSERT INTO accounts (id, username, email, password, salt, is_active) VALUES (:id, :username, :email, :password, :salt, true)';
    private const CHAR_COUNT_QUERY = 'INSERT INTO character_counts (owner_id, character_count, reached_limit) VALUES (:id, 0, false)';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SaltGenerator $saltGenerator,
        private UuidGenerator $uuidGenerator,
        private string $appEnv,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui = new SymfonyStyle($input, $output);

        if ('prod' === $this->appEnv) {
            $ui->error('This command is not meant to be run in production.');

            return Command::FAILURE;
        }

        /** @phpstan-ignore-next-line */
        $countOfAccountsToCreate = (int) max(0, $input->getOption(self::OPT_ACCOUNTS));

        $ui->text("<info>Populating the database with {$countOfAccountsToCreate} accounts...");

        try {
            $this->populateDatabase($ui, $countOfAccountsToCreate);
        } catch (Throwable $e) {
            $ui->error("An error occurred while populating the database: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $ui->success('Done populating the database with accounts.');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Populates the database with random fixtures for accounts.')
            ->addOption(self::OPT_ACCOUNTS, null, InputOption::VALUE_OPTIONAL, 'Number of accounts to create.', 5)
            ->setHelp(
                <<<'EOT'
The <info>%command.name%</info> populates the database with random fixtures for accounts.

    <info>php %command.full_name%</info>

Each account will use `UserX` as a username and `userX@example.com` as an email, where X is an identifier.
The password for the accounts will always be `password`.

You can also optionally specify an amount of account to create (5 by default):

    <info>php %command.full_name% --accounts=10</info>
EOT
            )
        ;
    }

    /**
     * @throws Exception
     */
    private function populateDatabase(OutputStyle $ui, int $countOfAccountsToCreate): void
    {
        $ui->progressStart($countOfAccountsToCreate);

        $connection = $this->entityManager->getConnection();
        for ($key = strtotime('now'), $i = 0; $i < $countOfAccountsToCreate; ++$i, ++$key) {
            $id = $this->uuidGenerator->uuid4();

            $params = [
                'id'       => $id,
                'username' => "User{$key}",
                'email'    => "user{$key}@example.com",
                'password' => password_hash('password', PASSWORD_ARGON2I),
                'salt'     => $this->saltGenerator->salt(),
            ];

            $connection->executeStatement(self::ACCOUNT_QUERY, $params);
            $connection->executeStatement(self::CHAR_COUNT_QUERY, ['id' => $id]);

            $ui->progressAdvance();
        }

        $ui->progressFinish();
    }
}
