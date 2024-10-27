<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241026230137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rsai_cover_letter ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_cover_letter ALTER sender TYPE JSON');
        $this->addSql('ALTER TABLE rsai_cover_letter ALTER receiver TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ADD is_favorite BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_cover_letter ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_cover_letter ALTER sender TYPE JSON');
        $this->addSql('ALTER TABLE rsai_cover_letter ALTER receiver TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job DROP is_favorite');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
