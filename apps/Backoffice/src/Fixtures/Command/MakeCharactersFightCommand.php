<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Fixtures\Command;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Kishlin\Backend\RPGIdleGame\Fight\Application\InitiateAFight\InitiateAFightCommand;
use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class MakeCharactersFightCommand extends Command
{
    private const NAME = 'kishlin:backoffice:fixtures:fights';

    private const OPT_SKIP_ONE_OUT_OF = 'skip';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommandBus $commandBus,
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
        $skipOneFightOutOf = (int) max(0, $input->getOption(self::OPT_SKIP_ONE_OUT_OF));

        if (1 === $skipOneFightOutOf) {
            $ui->text('<info>No fight to compute.</info>');

            return Command::SUCCESS;
        }

        try {
            $this->makeCharactersFight($ui, $skipOneFightOutOf);
        } catch (Throwable $e) {
            $ui->error("An error occurred while computing fights: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $ui->success('Done making the characters fight.');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Make some characters fights.')
            ->addOption(self::OPT_SKIP_ONE_OUT_OF, null, InputOption::VALUE_OPTIONAL, 'Likability to skip a fight.', 8)
            ->setHelp(
                <<<'EOT'
The <info>%command.name%</info> will make characters fight.

Some characters will not fight, by default a character has a one out of eight chance not to fight.
Optionally, you can specify how likely a character is to fight.

    <info>php %command.full_name% --skip-one-out-of=10</info>

Use 0 if you want to ensure all characters will fight.

    <info>php %command.full_name% --skip-one-out-of=0</info>
EOT
            )
        ;
    }

    /**
     * @throws Throwable
     */
    private function makeCharactersFight(OutputStyle $ui, int $skipOneFightOutOf): void
    {
        $connection = $this->entityManager->getConnection();

        /** @var array<array{id: string, owner: string}>|false $characters */
        $characters = $connection->fetchAllAssociative('SELECT id, owner FROM characters');

        if (empty($characters)) {
            $ui->text('<info>Did not find any character available to fight.</info>');

            return;
        }

        $ui->progressStart(count($characters));
        foreach ($characters as $character) {
            $this->makeTheCharacterFight($character, $skipOneFightOutOf);
            $ui->progressAdvance();
        }

        $ui->progressFinish();
    }

    /**
     * @param array{id: string, owner: string} $character
     *
     * @throws Exception
     */
    private function makeTheCharacterFight(array $character, int $skipOneFightOutOf): void
    {
        if (0 !== $skipOneFightOutOf && 1 === random_int(1, $skipOneFightOutOf)) {
            return;
        }

        $command = InitiateAFightCommand::fromScalars($character['id'], $character['owner']);

        $this->commandBus->execute($command);
    }
}
