<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505002827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club_membership (id INT AUTO_INCREMENT NOT NULL, shooter_id INT DEFAULT NULL, club_id INT DEFAULT NULL, INDEX IDX_CF399C68F42D3895 (shooter_id), INDEX IDX_CF399C6861190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE club_membership ADD CONSTRAINT FK_CF399C68F42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE club_membership ADD CONSTRAINT FK_CF399C6861190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE club ADD isowner TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE year_snapshot CHANGE average_hits average_hits NUMERIC(6, 2) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club_membership DROP FOREIGN KEY FK_CF399C68F42D3895');
        $this->addSql('ALTER TABLE club_membership DROP FOREIGN KEY FK_CF399C6861190A32');
        $this->addSql('DROP TABLE club_membership');
        $this->addSql('ALTER TABLE club DROP isowner');
        $this->addSql('ALTER TABLE year_snapshot CHANGE average_hits average_hits NUMERIC(6, 2) DEFAULT \'0.00\' NOT NULL');
    }
}
