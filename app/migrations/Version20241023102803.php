<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023102803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rsai_cover_letter DROP CONSTRAINT fk_d1609ecfdff2e92');
        $this->addSql('DROP INDEX idx_d1609ecfdff2e92');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD job_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD prompt_tips TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD prompt_framework TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD employer JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD sender JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD receiver JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP thumbnail_id');
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
        $this->addSql('ALTER TABLE rsai_cover_letter ADD thumbnail_id VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP job_title');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP prompt_tips');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP prompt_framework');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP employer');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP sender');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP receiver');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD CONSTRAINT fk_d1609ecfdff2e92 FOREIGN KEY (thumbnail_id) REFERENCES rsai_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d1609ecfdff2e92 ON rsai_cover_letter (thumbnail_id)');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
    }
}
