<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003223001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_location (id VARCHAR(128) NOT NULL, country VARCHAR(1000) DEFAULT NULL, city VARCHAR(1000) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, address TEXT DEFAULT NULL, region VARCHAR(1000) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rsai_user_employer (id UUID NOT NULL, location_id VARCHAR(128) DEFAULT NULL, owner_id UUID DEFAULT NULL, title VARCHAR(1000) DEFAULT NULL, about_page VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7FC6C66464D218E ON rsai_user_employer (location_id)');
        $this->addSql('CREATE INDEX IDX_7FC6C6647E3C61F9 ON rsai_user_employer (owner_id)');
        $this->addSql('COMMENT ON COLUMN rsai_user_employer.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_user_employer.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_user_employer ADD CONSTRAINT FK_7FC6C66464D218E FOREIGN KEY (location_id) REFERENCES rsai_location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_user_employer ADD CONSTRAINT FK_7FC6C6647E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_user_employer DROP CONSTRAINT FK_7FC6C66464D218E');
        $this->addSql('ALTER TABLE rsai_user_employer DROP CONSTRAINT FK_7FC6C6647E3C61F9');
        $this->addSql('DROP TABLE rsai_location');
        $this->addSql('DROP TABLE rsai_user_employer');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
