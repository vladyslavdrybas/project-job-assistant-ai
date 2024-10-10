<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010113304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_achievement (id UUID NOT NULL, employment_id UUID DEFAULT NULL, owner_id UUID DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(1200) DEFAULT NULL, done_at DATE DEFAULT NULL, skills JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_88F3D873466E61E3 ON rsai_achievement (employment_id)');
        $this->addSql('CREATE INDEX IDX_88F3D8737E3C61F9 ON rsai_achievement (owner_id)');
        $this->addSql('COMMENT ON COLUMN rsai_achievement.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_achievement.employment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_achievement.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_achievement.done_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE rsai_achievement ADD CONSTRAINT FK_88F3D873466E61E3 FOREIGN KEY (employment_id) REFERENCES rsai_employment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_achievement ADD CONSTRAINT FK_88F3D8737E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_achievement DROP CONSTRAINT FK_88F3D873466E61E3');
        $this->addSql('ALTER TABLE rsai_achievement DROP CONSTRAINT FK_88F3D8737E3C61F9');
        $this->addSql('DROP TABLE rsai_achievement');
        $this->addSql('ALTER TABLE rsai_job ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_job ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
        $this->addSql('ALTER TABLE rsai_resume ALTER personal_details TYPE JSON');
    }
}
