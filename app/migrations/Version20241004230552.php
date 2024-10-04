<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004230552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_job_skill (job_id UUID NOT NULL, skill_id UUID NOT NULL, PRIMARY KEY(job_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_B49D06D3BE04EA9 ON rsai_job_skill (job_id)');
        $this->addSql('CREATE INDEX IDX_B49D06D35585C142 ON rsai_job_skill (skill_id)');
        $this->addSql('COMMENT ON COLUMN rsai_job_skill.job_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_job_skill.skill_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_job_skill ADD CONSTRAINT FK_B49D06D3BE04EA9 FOREIGN KEY (job_id) REFERENCES rsai_job (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_job_skill ADD CONSTRAINT FK_B49D06D35585C142 FOREIGN KEY (skill_id) REFERENCES rsai_skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ADD location_id VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD about_page VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD formats JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD skills JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD employer JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD contact_person JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD CONSTRAINT FK_96AA6CEF64D218E FOREIGN KEY (location_id) REFERENCES rsai_location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_96AA6CEF64D218E ON rsai_job (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_job_skill DROP CONSTRAINT FK_B49D06D3BE04EA9');
        $this->addSql('ALTER TABLE rsai_job_skill DROP CONSTRAINT FK_B49D06D35585C142');
        $this->addSql('DROP TABLE rsai_job_skill');
        $this->addSql('ALTER TABLE rsai_job DROP CONSTRAINT FK_96AA6CEF64D218E');
        $this->addSql('DROP INDEX IDX_96AA6CEF64D218E');
        $this->addSql('ALTER TABLE rsai_job DROP location_id');
        $this->addSql('ALTER TABLE rsai_job DROP about_page');
        $this->addSql('ALTER TABLE rsai_job DROP formats');
        $this->addSql('ALTER TABLE rsai_job DROP skills');
        $this->addSql('ALTER TABLE rsai_job DROP employer');
        $this->addSql('ALTER TABLE rsai_job DROP contact_person');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
