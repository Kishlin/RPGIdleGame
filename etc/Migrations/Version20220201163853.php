<?php

declare(strict_types=1);

namespace Kishlin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightInitiator;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightOpponent;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\FightTurn;

final class Version20220201163853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return sprintf(
            'Create the table for %s, %s, %s, %s.',
            Fight::class,
            FightTurn::class,
            FightInitiator::class,
            FightOpponent::class
        );
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fight_initiators (id VARCHAR(36) NOT NULL, character_id VARCHAR(36) NOT NULL, health INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, magik INT NOT NULL, rank INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fight_opponents (id VARCHAR(36) NOT NULL, character_id VARCHAR(36) NOT NULL, health INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, magik INT NOT NULL, rank INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fight_turns (id VARCHAR(36) NOT NULL, fight_id VARCHAR(255) NOT NULL, attacker_id VARCHAR(36) NOT NULL, index INT NOT NULL, attacker_attack INT NOT NULL, attacker_magik INT NOT NULL, attacker_dice_roll INT NOT NULL, defender_defense INT NOT NULL, damage_dealt INT NOT NULL, defender_health INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fights (id VARCHAR(36) NOT NULL, initiator VARCHAR(255) NOT NULL, opponent VARCHAR(255) NOT NULL, winner_id VARCHAR(36) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_15531764AC6657E4 ON fight_turns (fight_id)');
        $this->addSql('CREATE INDEX IDX_9927918E451BF597 ON fights (initiator)');
        $this->addSql('CREATE INDEX IDX_9927918EA9322AFF ON fights (opponent)');;
        $this->addSql('ALTER TABLE fight_turns ADD CONSTRAINT FK_15531764AC6657E4 FOREIGN KEY (fight_id) REFERENCES fights (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fights ADD CONSTRAINT FK_9927918E451BF597 FOREIGN KEY (initiator) REFERENCES fight_initiators (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fights ADD CONSTRAINT FK_9927918EA9322AFF FOREIGN KEY (opponent) REFERENCES fight_opponents (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight_initiators ADD CONSTRAINT FK_INITIATOR_CHARACTER FOREIGN KEY (character_id) REFERENCES characters (character_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fight_opponents ADD CONSTRAINT FK_OPPONENT_CHARACTER FOREIGN KEY (character_id) REFERENCES characters (character_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fight_initiators DROP CONSTRAINT FK_INITIATOR_CHARACTER');
        $this->addSql('ALTER TABLE fight_opponents DROP CONSTRAINT FK_OPPONENT_CHARACTER');
        $this->addSql('ALTER TABLE fights DROP CONSTRAINT FK_9927918E451BF597');
        $this->addSql('ALTER TABLE fights DROP CONSTRAINT FK_9927918EA9322AFF');
        $this->addSql('ALTER TABLE fight_turns DROP CONSTRAINT FK_15531764AC6657E4');
        $this->addSql('DROP TABLE fight_initiators');
        $this->addSql('DROP TABLE fight_opponents');
        $this->addSql('DROP TABLE fight_turns');
        $this->addSql('DROP TABLE fights');
    }
}
