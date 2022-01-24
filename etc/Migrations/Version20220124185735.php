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
        $this->addSql('CREATE TABLE characters (character_id VARCHAR(36) NOT NULL, character_owner VARCHAR(36) NOT NULL, character_name VARCHAR(255) NOT NULL, character_skill_points INT NOT NULL, character_health INT NOT NULL, character_attack INT NOT NULL, character_defense INT NOT NULL, character_magik INT NOT NULL, character_rank INT NOT NULL, character_fights_count INT NOT NULL, PRIMARY KEY(character_id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE characters');
    }
}
