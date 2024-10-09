<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008010149 extends AbstractMigration
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
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ADD personal_details JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_resume DROP firstname');
        $this->addSql('ALTER TABLE rsai_resume DROP lastname');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ADD firstname VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_resume ADD lastname VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_resume DROP personal_details');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
    }
}
