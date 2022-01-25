<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Kishlin\Backend\RPGIdleGame\CharacterCount\Domain\CharacterCount;

final class Version20220125013925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table for ' . CharacterCount::class;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE character_counts (owner_id VARCHAR(36) NOT NULL, character_count INT NOT NULL, character_count_reached_limit BOOLEAN NOT NULL, PRIMARY KEY(owner_id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE character_counts');
    }
}
