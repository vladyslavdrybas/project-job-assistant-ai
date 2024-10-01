<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001142704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_employment (id UUID NOT NULL, owner_id UUID DEFAULT NULL, job_title VARCHAR(255) DEFAULT NULL, project_title VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3E5D760A7E3C61F9 ON rsai_employment (owner_id)');
        $this->addSql('COMMENT ON COLUMN rsai_employment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_employment.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_employment ADD CONSTRAINT FK_3E5D760A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_job ALTER status SET DEFAULT \'backlog\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_employment DROP CONSTRAINT FK_3E5D760A7E3C61F9');
        $this->addSql('DROP TABLE rsai_employment');
        $this->addSql('ALTER TABLE rsai_job ALTER status SET DEFAULT \'new\'');
    }
}
