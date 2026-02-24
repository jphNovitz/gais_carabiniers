<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251023033933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round_shot ADD left_hit TINYINT(1) NOT NULL, ADD lefttarget SMALLINT DEFAULT NULL, ADD right_hit TINYINT(1) NOT NULL, ADD right_target SMALLINT DEFAULT NULL, DROP targets, CHANGE score score SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round_shot ADD targets JSON NOT NULL, DROP left_hit, DROP lefttarget, DROP right_hit, DROP right_target, CHANGE score score INT DEFAULT NULL');
    }
}
