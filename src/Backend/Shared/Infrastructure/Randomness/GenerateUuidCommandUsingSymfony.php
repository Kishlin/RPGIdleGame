<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Randomness;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class GenerateUuidCommandUsingSymfony extends Command
{
    private const NAME = 'kishlin:shared:randomness:uuid';

    public function __construct(
        private UuidGeneratorUsingRamsey $uuidGeneratorUsingRamsey,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Generate a uuid using Ramsey.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ui   = new SymfonyStyle($input, $output);
        $uuid = $this->uuidGeneratorUsingRamsey->uuid4();

        $ui->text(sprintf("<info>Generated uuid: %s</info>\n", $uuid));

        return Command::SUCCESS;
    }
}
