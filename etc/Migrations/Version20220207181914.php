<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220207181914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add stats column to the characters table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE characters ALTER fights_count SET DEFAULT 0;');
        $this->addSql('ALTER TABLE characters ADD wins_count INT NOT NULL DEFAULT 0;');
        $this->addSql('ALTER TABLE characters ADD draws_count INT NOT NULL DEFAULT 0;');
        $this->addSql('ALTER TABLE characters ADD losses_count INT NOT NULL DEFAULT 0;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE characters ALTER fights_count DROP DEFAULT;');
        $this->addSql('ALTER TABLE characters DROP COLUMN wins_count;');
        $this->addSql('ALTER TABLE characters DROP COLUMN draws_count;');
        $this->addSql('ALTER TABLE characters DROP COLUMN losses_count;');
    }
}
