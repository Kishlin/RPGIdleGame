<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220228131426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add active status column to the characters table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE characters ADD is_active BOOLEAN NOT NULL;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE characters DROP COLUMN is_active;');
    }
}
