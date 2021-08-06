<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806001911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('DROP INDEX IDX_1FC0F36A9A353316');
        $this->addSql('CREATE TEMPORARY TABLE __temp__doctor AS SELECT id, specialty_id, crm, name FROM doctor');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('CREATE TABLE doctor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, specialty_id INTEGER NOT NULL, crm INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_1FC0F36A9A353316 FOREIGN KEY (specialty_id) REFERENCES specialty (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO doctor (id, specialty_id, crm, name) SELECT id, specialty_id, crm, name FROM __temp__doctor');
        $this->addSql('DROP TABLE __temp__doctor');
        $this->addSql('CREATE INDEX IDX_1FC0F36A9A353316 ON doctor (specialty_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_1FC0F36A9A353316');
        $this->addSql('CREATE TEMPORARY TABLE __temp__doctor AS SELECT id, specialty_id, crm, name FROM doctor');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('CREATE TABLE doctor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, specialty_id INTEGER NOT NULL, crm INTEGER NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO doctor (id, specialty_id, crm, name) SELECT id, specialty_id, crm, name FROM __temp__doctor');
        $this->addSql('DROP TABLE __temp__doctor');
        $this->addSql('CREATE INDEX IDX_1FC0F36A9A353316 ON doctor (specialty_id)');
    }
}
