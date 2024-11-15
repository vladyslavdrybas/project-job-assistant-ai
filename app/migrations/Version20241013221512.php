<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013221512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_open_ai_prompt (id UUID NOT NULL, owner_id UUID DEFAULT NULL, prompt_hash VARCHAR(255) DEFAULT NULL, model_name VARCHAR(255) DEFAULT NULL, prompt_meta JSON DEFAULT NULL, prompt_answer JSON DEFAULT NULL, is_done BOOLEAN DEFAULT false NOT NULL, request_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, execution_milliseconds INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_270959337E3C61F9 ON rsai_open_ai_prompt (owner_id)');
        $this->addSql('COMMENT ON COLUMN rsai_open_ai_prompt.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_open_ai_prompt.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_open_ai_prompt.execution_milliseconds IS \'milliseconds\'');
        $this->addSql('ALTER TABLE rsai_open_ai_prompt ADD CONSTRAINT FK_270959337E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_open_ai_prompt DROP CONSTRAINT FK_270959337E3C61F9');
        $this->addSql('DROP TABLE rsai_open_ai_prompt');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
    }
}
