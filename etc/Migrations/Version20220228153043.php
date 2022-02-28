<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220228153043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add availability and creation date columns to the characters table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE characters ADD created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE characters ADD available_as_of TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE characters DROP COLUMN created_on;');
        $this->addSql('ALTER TABLE characters DROP COLUMN available_as_of;');
    }
}
