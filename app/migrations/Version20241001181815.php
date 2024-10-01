<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001181815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rsai_employment ALTER start_date TYPE DATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER end_date TYPE DATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('COMMENT ON COLUMN rsai_employment.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN rsai_employment.end_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_employment ALTER start_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE rsai_employment ALTER end_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('COMMENT ON COLUMN rsai_employment.start_date IS NULL');
        $this->addSql('COMMENT ON COLUMN rsai_employment.end_date IS NULL');
    }
}
