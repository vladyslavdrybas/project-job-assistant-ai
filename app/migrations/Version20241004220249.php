<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241004220249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rsai_user_employer_contact_person (id UUID NOT NULL, location_id VARCHAR(128) DEFAULT NULL, user_employer_id UUID DEFAULT NULL, owner_id UUID DEFAULT NULL, first_name VARCHAR(1000) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_61A1BDB964D218E ON rsai_user_employer_contact_person (location_id)');
        $this->addSql('CREATE INDEX IDX_61A1BDB9E8D657EF ON rsai_user_employer_contact_person (user_employer_id)');
        $this->addSql('CREATE INDEX IDX_61A1BDB97E3C61F9 ON rsai_user_employer_contact_person (owner_id)');
        $this->addSql('COMMENT ON COLUMN rsai_user_employer_contact_person.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_user_employer_contact_person.user_employer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN rsai_user_employer_contact_person.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rsai_user_employer_contact_person ADD CONSTRAINT FK_61A1BDB964D218E FOREIGN KEY (location_id) REFERENCES rsai_location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_user_employer_contact_person ADD CONSTRAINT FK_61A1BDB9E8D657EF FOREIGN KEY (user_employer_id) REFERENCES rsai_user_employer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_user_employer_contact_person ADD CONSTRAINT FK_61A1BDB97E3C61F9 FOREIGN KEY (owner_id) REFERENCES rsai_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rsai_user_employer_contact_person DROP CONSTRAINT FK_61A1BDB964D218E');
        $this->addSql('ALTER TABLE rsai_user_employer_contact_person DROP CONSTRAINT FK_61A1BDB9E8D657EF');
        $this->addSql('ALTER TABLE rsai_user_employer_contact_person DROP CONSTRAINT FK_61A1BDB97E3C61F9');
        $this->addSql('DROP TABLE rsai_user_employer_contact_person');
        $this->addSql('ALTER TABLE rsai_employment ALTER employer TYPE JSON');
        $this->addSql('ALTER TABLE rsai_employment ALTER contact_person TYPE JSON');
    }
}
