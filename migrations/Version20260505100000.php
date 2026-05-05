<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260505100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add email and phone columns to member table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `member` ADD email VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `member` DROP email, DROP phone');
    }
}
