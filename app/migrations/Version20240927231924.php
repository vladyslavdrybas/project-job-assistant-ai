<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240927231924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_cover_letter (id UUID NOT NULL, owner_id UUID DEFAULT NULL, thumbnail_id VARCHAR(128) DEFAULT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D1609EC7E3C61F9 ON rsai_cover_letter (owner_id)');
        $this->addSql('CREATE INDEX IDX_D1609ECFDFF2E92 ON rsai_cover_letter (thumbnail_id)');
        $this->addSql('COMMENT ON COLUMN rsai_cover_letter.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_cover_letter.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD CONSTRAINT FK_D1609EC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_cover_letter ADD CONSTRAINT FK_D1609ECFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES rsai_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP CONSTRAINT FK_D1609EC7E3C61F9');
        $this->addSql('ALTER TABLE rsai_cover_letter DROP CONSTRAINT FK_D1609ECFDFF2E92');
        $this->addSql('DROP TABLE rsai_cover_letter');
    }
}
