<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920220644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_user_google (id UUID NOT NULL, google_id VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, full_name VARCHAR(200) DEFAULT NULL, first_name VARCHAR(200) DEFAULT NULL, last_name VARCHAR(200) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, is_email_verified BOOLEAN DEFAULT false NOT NULL, locale VARCHAR(255) DEFAULT NULL, hosted_domain VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ECE49EE76F5C865 ON rsai_user_google (google_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1ECE49EEE7927C74 ON rsai_user_google (email)');
        $this->addSql('COMMENT ON COLUMN rsai_user_google.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_user ALTER email TYPE VARCHAR(250)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE rsai_user_google');
        $this->addSql('ALTER TABLE rsai_user ALTER email TYPE VARCHAR(180)');
    }
}
