<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;

final class Version20220124185735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table for ' . Character::class;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE characters (id VARCHAR(36) NOT NULL, owner VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, skill_points INT NOT NULL, health INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, magik INT NOT NULL, rank INT NOT NULL, fights_count INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE characters');
    }
}
