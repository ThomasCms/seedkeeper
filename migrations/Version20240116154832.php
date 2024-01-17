<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240116154832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding 2fa related fields to table user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD google_authenticator_secret VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD backup_codes JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP google_authenticator_secret');
        $this->addSql('ALTER TABLE "user" DROP backup_codes');
    }
}
