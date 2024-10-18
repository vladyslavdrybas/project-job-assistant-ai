<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018165402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rsai_achievement ALTER description TYPE VARCHAR(2048)');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ADD resume_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD cover_letter_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('COMMENT ON COLUMN rsai_job.resume_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_job.cover_letter_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_job ADD CONSTRAINT FK_96AA6CEFD262AF09 FOREIGN KEY (resume_id) REFERENCES rsai_resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_job ADD CONSTRAINT FK_96AA6CEFB944729C FOREIGN KEY (cover_letter_id) REFERENCES rsai_cover_letter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_96AA6CEFD262AF09 ON rsai_job (resume_id)');
        $this->addSql('CREATE INDEX IDX_96AA6CEFB944729C ON rsai_job (cover_letter_id)');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_achievement ALTER description TYPE VARCHAR(1200)');
        $this->addSql('ALTER TABLE rsai_job DROP CONSTRAINT FK_96AA6CEFD262AF09');
        $this->addSql('ALTER TABLE rsai_job DROP CONSTRAINT FK_96AA6CEFB944729C');
        $this->addSql('DROP INDEX IDX_96AA6CEFD262AF09');
        $this->addSql('DROP INDEX IDX_96AA6CEFB944729C');
        $this->addSql('ALTER TABLE rsai_job DROP resume_id');
        $this->addSql('ALTER TABLE rsai_job DROP cover_letter_id');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
