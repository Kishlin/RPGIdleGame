<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Kishlin\Backend\Account\Domain\Account;

final class Version20220121150906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table for ' . Account::class;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE accounts (id VARCHAR(36) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE accounts');
    }
}
