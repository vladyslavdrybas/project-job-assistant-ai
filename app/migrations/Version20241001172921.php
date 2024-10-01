<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001172921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rsai_employment ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ADD start_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ADD end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ADD formats TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ADD skills TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ADD employer JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ADD contact_person JSON DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN rsai_employment.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN rsai_employment.end_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN rsai_employment.formats IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN rsai_employment.skills IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_employment DROP description');
        $this->addSql('ALTER TABLE rsai_employment DROP start_date');
        $this->addSql('ALTER TABLE rsai_employment DROP end_date');
        $this->addSql('ALTER TABLE rsai_employment DROP formats');
        $this->addSql('ALTER TABLE rsai_employment DROP skills');
        $this->addSql('ALTER TABLE rsai_employment DROP employer');
        $this->addSql('ALTER TABLE rsai_employment DROP contact_person');
    }
}
