<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916215509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pf_media (id VARCHAR(128) NOT NULL, owner_id UUID DEFAULT NULL, mimetype VARCHAR(255) NOT NULL, extension VARCHAR(20) NOT NULL, size INT DEFAULT 0 NOT NULL, path VARCHAR(255) NOT NULL, media_tag VARCHAR(255) DEFAULT \'user\' NOT NULL, server_alias VARCHAR(255) DEFAULT \'local\' NOT NULL, version SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70C2DB157E3C61F9 ON pf_media (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX owner_id_idx ON pf_media (owner_id, id)');
        $this->addSql('COMMENT ON COLUMN pf_media.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE pf_media_tag (media_id VARCHAR(128) NOT NULL, tag_id VARCHAR(255) NOT NULL, PRIMARY KEY(media_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_13017F5EA9FDD75 ON pf_media_tag (media_id)');
        $this->addSql('CREATE INDEX IDX_13017F5BAD26311 ON pf_media_tag (tag_id)');
        $this->addSql('CREATE TABLE pf_request_call_back (id UUID NOT NULL, hash VARCHAR(64) NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, description VARCHAR(4096) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C477FFBD1B862B8 ON pf_request_call_back (hash)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C477FFBE7927C74 ON pf_request_call_back (email)');
        $this->addSql('COMMENT ON COLUMN pf_request_call_back.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE pf_tag (id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pf_user (id UUID NOT NULL, roles JSON NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(100) NOT NULL, firstname VARCHAR(100) DEFAULT NULL, lastname VARCHAR(100) DEFAULT NULL, password VARCHAR(100) NOT NULL, is_email_verified BOOLEAN DEFAULT false NOT NULL, is_active BOOLEAN DEFAULT true NOT NULL, is_banned BOOLEAN DEFAULT false NOT NULL, is_deleted BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B93DB25DE7927C74 ON pf_user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B93DB25DF85E0677 ON pf_user (username)');
        $this->addSql('COMMENT ON COLUMN pf_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE pf_media ADD CONSTRAINT FK_70C2DB157E3C61F9 FOREIGN KEY (owner_id) REFERENCES pf_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pf_media_tag ADD CONSTRAINT FK_13017F5EA9FDD75 FOREIGN KEY (media_id) REFERENCES pf_media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pf_media_tag ADD CONSTRAINT FK_13017F5BAD26311 FOREIGN KEY (tag_id) REFERENCES pf_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pf_media DROP CONSTRAINT FK_70C2DB157E3C61F9');
        $this->addSql('ALTER TABLE pf_media_tag DROP CONSTRAINT FK_13017F5EA9FDD75');
        $this->addSql('ALTER TABLE pf_media_tag DROP CONSTRAINT FK_13017F5BAD26311');
        $this->addSql('DROP TABLE pf_media');
        $this->addSql('DROP TABLE pf_media_tag');
        $this->addSql('DROP TABLE pf_request_call_back');
        $this->addSql('DROP TABLE pf_tag');
        $this->addSql('DROP TABLE pf_user');
    }
}
