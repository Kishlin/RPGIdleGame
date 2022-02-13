<?php

declare(strict_types=1);

namespace Kishlin\Apps\Backoffice\Fixtures\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class DistributeSkillPointsCommand extends Command
{
    private const NAME = 'kishlin:backoffice:fixtures:skill-points';

    private const STATS_MAP = ['health', 'attack', 'defense', 'magik'];
    private const QUERY     = 'UPDATE characters SET skill_points = :skill_points, health = :health, attack = :attack, defense = :defense, magik = :magik WHERE id = :id';

    public function __construct(
        private EntityManagerInterface $entityManager,
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

        try {
            $this->distributeSkillPoints($ui);
        } catch (Throwable $e) {
            $ui->error("An error occurred while distributing skill points: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $ui->success('Done distributing skill points for all characters.');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Distribute skill points for character which have any.')
            ->setHelp(
                <<<'EOT'
The <info>%command.name%</info> will distribute skill points on every character which have any.

    <info>php %command.full_name%</info>
EOT
            )
        ;
    }

    /**
     * @throws Throwable
     */
    private function distributeSkillPoints(OutputStyle $ui): void
    {
        $connection = $this->entityManager->getConnection();

        /** @var array<array{id: string, skill_points: int, health: int, attack: int, defense: int, magik: int}>|false $characters */
        $characters = $connection->fetchAllAssociative(
            'SELECT id, skill_points, health, attack, defense, magik FROM characters WHERE skill_points > 0',
        );

        if (empty($characters)) {
            $ui->text('<info>Did not find any character with skill points available.</info>');

            return;
        }

        $ui->progressStart(count($characters));
        foreach ($characters as $character) {
            $this->distributeSkillPointsForCharacter($character);
            $ui->progressAdvance();
        }

        $ui->progressFinish();
    }

    /**
     * @param array{id: string, skill_points: int, health: int, attack: int, defense: int, magik: int} $character
     *
     * @throws Throwable
     */
    private function distributeSkillPointsForCharacter(array $character): void
    {
        $price        = 0;
        $toDistribute = array_fill_keys(self::STATS_MAP, 0);

        while ($price < $character['skill_points']) {
            $target = random_int(0, 3);
            $stat   = self::STATS_MAP[$target];

            if (3 === $target && $character['magik'] + $toDistribute['magik'] >= $character['attack'] + $toDistribute['attack']) {
                continue; // Do not let magik get higher than attack (dice will never roll higher than attack).
            }

            // Health costs 1, otherwise the wanted level divided by 5 rounded up.
            $willCost = (0 === $target ? 1 : (int) (ceil(($character[$stat] + $toDistribute[$stat] + 1) / 5)));

            if ($price + $willCost > $character['skill_points']) {
                break;
            }

            $price += $willCost;
            ++$toDistribute[$stat];
        }

        $params = [
            'id'           => $character['id'],
            'skill_points' => $character['skill_points'] - $price,
            'health'       => $character['health'] + $toDistribute['health'],
            'attack'       => $character['attack'] + $toDistribute['attack'],
            'defense'      => $character['defense'] + $toDistribute['defense'],
            'magik'        => $character['magik'] + $toDistribute['magik'],
        ];

        $this->entityManager->getConnection()->executeStatement(self::QUERY, $params);
    }
}
