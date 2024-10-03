<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003004523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_user_skill (user_id UUID NOT NULL, skill_id UUID NOT NULL, PRIMARY KEY(user_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_3DAAF5BDA76ED395 ON rsai_user_skill (user_id)');
        $this->addSql('CREATE INDEX IDX_3DAAF5BD5585C142 ON rsai_user_skill (skill_id)');
        $this->addSql('COMMENT ON COLUMN rsai_user_skill.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_user_skill.skill_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_user_skill ADD CONSTRAINT FK_3DAAF5BDA76ED395 FOREIGN KEY (user_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_user_skill ADD CONSTRAINT FK_3DAAF5BD5585C142 FOREIGN KEY (skill_id) REFERENCES rsai_skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_user_skill DROP CONSTRAINT FK_3DAAF5BDA76ED395');
        $this->addSql('ALTER TABLE rsai_user_skill DROP CONSTRAINT FK_3DAAF5BD5585C142');
        $this->addSql('DROP TABLE rsai_user_skill');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
