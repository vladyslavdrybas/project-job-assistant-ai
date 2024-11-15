<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241006094111 extends AbstractMigration
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
        $this->addSql('ALTER TABLE rsai_job ADD salary_min INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD salary_max INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ADD salary_period VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job DROP salary_min');
        $this->addSql('ALTER TABLE rsai_job DROP salary_max');
        $this->addSql('ALTER TABLE rsai_job DROP salary_period');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
    }
}
