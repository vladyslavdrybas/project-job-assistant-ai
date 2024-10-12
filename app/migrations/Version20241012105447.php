<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241012105447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_interview_question ADD is_default BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE rsai_interview_question ADD is_public BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE rsai_interview_question DROP "default"');
        $this->addSql('ALTER TABLE rsai_interview_question DROP public');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_interview_question ADD "default" BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE rsai_interview_question ADD public BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE rsai_interview_question DROP is_default');
        $this->addSql('ALTER TABLE rsai_interview_question DROP is_public');
    }
}
