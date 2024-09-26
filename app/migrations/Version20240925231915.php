<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925231915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_job_skill (id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B49D06D32B36786B ON rsai_job_skill (title)');
        $this->addSql('COMMENT ON COLUMN rsai_job_skill.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE rsai_resume (id UUID NOT NULL, owner_id UUID DEFAULT NULL, pdf_media_id VARCHAR(128) DEFAULT NULL, thumbnail_id VARCHAR(128) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, job_title VARCHAR(255) DEFAULT NULL, email VARCHAR(250) DEFAULT NULL, firstname VARCHAR(100) DEFAULT NULL, lastname VARCHAR(100) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5095A26F7E3C61F9 ON rsai_resume (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5095A26F413D5AAD ON rsai_resume (pdf_media_id)');
        $this->addSql('CREATE INDEX IDX_5095A26FFDFF2E92 ON rsai_resume (thumbnail_id)');
        $this->addSql('COMMENT ON COLUMN rsai_resume.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_resume.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE rsai_resume_job_skill (resume_id UUID NOT NULL, job_skill_id UUID NOT NULL, PRIMARY KEY(resume_id, job_skill_id))');
        $this->addSql('CREATE INDEX IDX_BCF65EAFD262AF09 ON rsai_resume_job_skill (resume_id)');
        $this->addSql('CREATE INDEX IDX_BCF65EAF32C26439 ON rsai_resume_job_skill (job_skill_id)');
        $this->addSql('COMMENT ON COLUMN rsai_resume_job_skill.resume_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_resume_job_skill.job_skill_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_resume ADD CONSTRAINT FK_5095A26F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume ADD CONSTRAINT FK_5095A26F413D5AAD FOREIGN KEY (pdf_media_id) REFERENCES rsai_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume ADD CONSTRAINT FK_5095A26FFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES rsai_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume_job_skill ADD CONSTRAINT FK_BCF65EAFD262AF09 FOREIGN KEY (resume_id) REFERENCES rsai_resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume_job_skill ADD CONSTRAINT FK_BCF65EAF32C26439 FOREIGN KEY (job_skill_id) REFERENCES rsai_job_skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_resume DROP CONSTRAINT FK_5095A26F7E3C61F9');
        $this->addSql('ALTER TABLE rsai_resume DROP CONSTRAINT FK_5095A26F413D5AAD');
        $this->addSql('ALTER TABLE rsai_resume DROP CONSTRAINT FK_5095A26FFDFF2E92');
        $this->addSql('ALTER TABLE rsai_resume_job_skill DROP CONSTRAINT FK_BCF65EAFD262AF09');
        $this->addSql('ALTER TABLE rsai_resume_job_skill DROP CONSTRAINT FK_BCF65EAF32C26439');
        $this->addSql('DROP TABLE rsai_job_skill');
        $this->addSql('DROP TABLE rsai_resume');
        $this->addSql('DROP TABLE rsai_resume_job_skill');
    }
}
