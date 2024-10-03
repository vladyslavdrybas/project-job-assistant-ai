<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003003628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_resume_skill (resume_id UUID NOT NULL, skill_id UUID NOT NULL, PRIMARY KEY(resume_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_C16246B4D262AF09 ON rsai_resume_skill (resume_id)');
        $this->addSql('CREATE INDEX IDX_C16246B45585C142 ON rsai_resume_skill (skill_id)');
        $this->addSql('COMMENT ON COLUMN rsai_resume_skill.resume_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_resume_skill.skill_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE rsai_skill (id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFB0C2982B36786B ON rsai_skill (title)');
        $this->addSql('COMMENT ON COLUMN rsai_skill.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_resume_skill ADD CONSTRAINT FK_C16246B4D262AF09 FOREIGN KEY (resume_id) REFERENCES rsai_resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume_skill ADD CONSTRAINT FK_C16246B45585C142 FOREIGN KEY (skill_id) REFERENCES rsai_skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume_job_skill DROP CONSTRAINT fk_bcf65eafd262af09');
        $this->addSql('ALTER TABLE rsai_resume_job_skill DROP CONSTRAINT fk_bcf65eaf32c26439');
        $this->addSql('DROP TABLE rsai_job_skill');
        $this->addSql('DROP TABLE rsai_resume_job_skill');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE rsai_job_skill (id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_b49d06d32b36786b ON rsai_job_skill (title)');
        $this->addSql('COMMENT ON COLUMN rsai_job_skill.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE rsai_resume_job_skill (resume_id UUID NOT NULL, job_skill_id UUID NOT NULL, PRIMARY KEY(resume_id, job_skill_id))');
        $this->addSql('CREATE INDEX idx_bcf65eaf32c26439 ON rsai_resume_job_skill (job_skill_id)');
        $this->addSql('CREATE INDEX idx_bcf65eafd262af09 ON rsai_resume_job_skill (resume_id)');
        $this->addSql('COMMENT ON COLUMN rsai_resume_job_skill.resume_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_resume_job_skill.job_skill_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_resume_job_skill ADD CONSTRAINT fk_bcf65eafd262af09 FOREIGN KEY (resume_id) REFERENCES rsai_resume (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume_job_skill ADD CONSTRAINT fk_bcf65eaf32c26439 FOREIGN KEY (job_skill_id) REFERENCES rsai_job_skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_resume_skill DROP CONSTRAINT FK_C16246B4D262AF09');
        $this->addSql('ALTER TABLE rsai_resume_skill DROP CONSTRAINT FK_C16246B45585C142');
        $this->addSql('DROP TABLE rsai_resume_skill');
        $this->addSql('DROP TABLE rsai_skill');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
