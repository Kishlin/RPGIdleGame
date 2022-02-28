<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220228160006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date column to the fights table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fights ADD fight_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fights DROP COLUMN fight_date;');
    }
}
