<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260505103000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add address columns to member table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `member` ADD street VARCHAR(255) DEFAULT NULL, ADD street_number VARCHAR(50) DEFAULT NULL, ADD postal_code VARCHAR(50) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `member` DROP street, DROP street_number, DROP postal_code, DROP city');
    }
}
