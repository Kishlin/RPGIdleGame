<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Fixtures\Command;

use Doctrine\ORM\EntityManagerInterface;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;
use Kishlin\Backend\Shared\Domain\Randomness\UuidGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class CreateCharactersCommand extends Command
{
    private const NAME = 'kishlin:backoffice:fixtures:characters';

    private const OPT_MIN_CHAR_PER_ACCOUNT = 'min';
    private const OPT_MAX_CHAR_PER_ACCOUNT = 'max';

    private const CHAR_COUNT_QUERY = 'UPDATE character_counts SET character_count = :character_count, reached_limit = :reached_limit WHERE owner_id = :owner';
    private const CHARACTER_QUERY  = <<<'SQL'
INSERT INTO characters (id, owner, name, skill_points, health, attack, defense, magik, rank, is_active)
VALUES (:id, :owner, :character_name, 12, 10, 0, 0, 0, 1, true)
SQL;

    public function __construct(
        private EntityManagerInterface $entityManager,
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
        $minCountOfCharacters = (int) max(0, $input->getOption(self::OPT_MIN_CHAR_PER_ACCOUNT));

        /** @phpstan-ignore-next-line */
        $maxCountOfCharacters = (int) max(1, $input->getOption(self::OPT_MAX_CHAR_PER_ACCOUNT));

        try {
            $this->populateDatabase($ui, $minCountOfCharacters, $maxCountOfCharacters);
        } catch (Throwable $e) {
            $ui->error("An error occurred while populating the database: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $ui->success('Done populating the database with characters.');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Populates the database with random fixtures for characters.')
            ->addOption(self::OPT_MIN_CHAR_PER_ACCOUNT, null, InputOption::VALUE_OPTIONAL, 'Min number of character per accounts to create.', 1)
            ->addOption(self::OPT_MAX_CHAR_PER_ACCOUNT, null, InputOption::VALUE_OPTIONAL, 'Max number of character per accounts to create.', 10)
            ->setHelp(
                <<<'EOT'
The <info>%command.name%</info> populates the database with random fixtures for characters.

    <info>php %command.full_name%</info>

Each character will use `CharacterX` as a name, where X is an identifier.
Defaults stats are being used: 12 skill points, 10 health, 0 attack defense magik, rank 1.

You can also optionally specify min/max amounts of characters to create (1 to 10 by default):

    <info>php %command.full_name% --min 5 --max 8</info>
EOT
            )
        ;
    }

    /**
     * @throws Throwable
     */
    private function populateDatabase(OutputStyle $ui, int $minCountOfCharacters, int $maxCountOfCharacter): void
    {
        /** @var array<array{owner_id: string, character_count: int}>|false $owners */
        $owners = $this->entityManager->getConnection()->fetchAllAssociative(
            'SELECT owner_id, character_count FROM character_counts WHERE reached_limit = false'
        );

        if (empty($owners)) {
            $ui->text('<info>Did not find any account with free character slots.</info>');

            return;
        }

        $count = count($owners);
        $ui->text("<info>Populating the database with characters for {$count} accounts...</info>");
        $ui->progressStart($count);

        foreach ($owners as $owner) {
            $maxSlotsLeft = CharacterCount::CHARACTER_LIMIT - $owner['character_count'];
            $maxPossible  = min($maxSlotsLeft, $maxCountOfCharacter);

            $charactersToCreate = $minCountOfCharacters < $maxPossible ?
                random_int($minCountOfCharacters, $maxPossible) :
                $maxPossible
            ;

            $this->addCharacters($owner['owner_id'], $charactersToCreate);
            $this->updateCharacterCount($owner['owner_id'], $charactersToCreate + $owner['character_count']);

            $ui->progressAdvance();
        }

        $ui->progressFinish();
    }

    /**
     * @throws Throwable
     */
    private function addCharacters(string $owner, int $countOfCharacters): void
    {
        $connection = $this->entityManager->getConnection();

        for ($key = strtotime('now'), $i = 0; $i < $countOfCharacters; ++$i, ++$key) {
            $id = $this->uuidGenerator->uuid4();

            $params = [
                'id'             => $id,
                'owner'          => $owner,
                'character_name' => "Character{$key}",
            ];

            $connection->executeStatement(self::CHARACTER_QUERY, $params);
        }
    }

    /**
     * @throws Throwable
     */
    private function updateCharacterCount(string $owner, int $totalCharacters): void
    {
        $params = [
            'character_count' => $totalCharacters,
            'reached_limit'   => CharacterCount::CHARACTER_LIMIT === $totalCharacters ? 'true' : 'false',
            'owner'           => $owner,
        ];

        $this->entityManager->getConnection()->executeStatement(self::CHAR_COUNT_QUERY, $params);
    }
}
