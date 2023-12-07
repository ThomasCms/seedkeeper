<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231129093342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the seed table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE seed_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE seed (id INT NOT NULL, owner_id INT NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4487E3067E3C61F9 ON seed (owner_id)');
        $this->addSql('ALTER TABLE seed ADD CONSTRAINT FK_4487E3067E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE seed_id_seq CASCADE');
        $this->addSql('ALTER TABLE seed DROP CONSTRAINT FK_4487E3067E3C61F9');
        $this->addSql('DROP TABLE seed');
    }
}
