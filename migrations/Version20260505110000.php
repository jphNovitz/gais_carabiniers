<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260505110000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add profile image columns to member table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `member` ADD profile_image_name VARCHAR(255) DEFAULT NULL, ADD profile_image_size INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `member` DROP profile_image_name, DROP profile_image_size');
    }
}
