<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001180235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rsai_employment ADD formats JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ADD skills JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_employment DROP formats');
        $this->addSql('ALTER TABLE rsai_employment DROP skills');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
