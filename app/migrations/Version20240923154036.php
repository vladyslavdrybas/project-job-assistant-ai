<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923154036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_user_linkedin (id UUID NOT NULL, owner_id UUID DEFAULT NULL, o_auth_id VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, full_name VARCHAR(200) DEFAULT NULL, first_name VARCHAR(200) DEFAULT NULL, last_name VARCHAR(200) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, is_email_verified BOOLEAN DEFAULT false NOT NULL, locale VARCHAR(255) DEFAULT NULL, hosted_domain VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCCD85A3F43BE553 ON rsai_user_linkedin (o_auth_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DCCD85A3E7927C74 ON rsai_user_linkedin (email)');
        $this->addSql('CREATE INDEX IDX_DCCD85A37E3C61F9 ON rsai_user_linkedin (owner_id)');
        $this->addSql('COMMENT ON COLUMN rsai_user_linkedin.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_user_linkedin.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_user_linkedin ADD CONSTRAINT FK_DCCD85A37E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_user ALTER email DROP NOT NULL');
        $this->addSql('DROP INDEX uniq_1ece49ee76f5c865');
        $this->addSql('ALTER TABLE rsai_user_google ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE rsai_user_google RENAME COLUMN google_id TO o_auth_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ECE49EEF43BE553 ON rsai_user_google (o_auth_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_user_linkedin DROP CONSTRAINT FK_DCCD85A37E3C61F9');
        $this->addSql('DROP TABLE rsai_user_linkedin');
        $this->addSql('DROP INDEX UNIQ_1ECE49EEF43BE553');
        $this->addSql('ALTER TABLE rsai_user_google ALTER email SET NOT NULL');
        $this->addSql('ALTER TABLE rsai_user_google RENAME COLUMN o_auth_id TO google_id');
        $this->addSql('CREATE UNIQUE INDEX uniq_1ece49ee76f5c865 ON rsai_user_google (google_id)');
        $this->addSql('ALTER TABLE rsai_user ALTER email SET NOT NULL');
    }
}
