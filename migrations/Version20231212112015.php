<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231212112015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a title to seed';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE seed ADD title VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE seed DROP title');
    }
}
