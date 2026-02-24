<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104005635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meeting_result (id INT AUTO_INCREMENT NOT NULL, meeting_id INT NOT NULL, shooter_id INT NOT NULL, position INT NOT NULL, points DOUBLE PRECISION NOT NULL, score_total DOUBLE PRECISION NOT NULL, computed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', shooter_name VARCHAR(255) NOT NULL, club_name VARCHAR(255) DEFAULT NULL, meeting_label VARCHAR(255) NOT NULL, year INT NOT NULL, INDEX IDX_1302F05167433D9C (meeting_id), INDEX IDX_1302F051F42D3895 (shooter_id), INDEX idx_points (year, points), INDEX idx_meeting_position (meeting_id, position), UNIQUE INDEX uniq_meeting_shooter (meeting_id, shooter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting_result ADD CONSTRAINT FK_1302F05167433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meeting_result ADD CONSTRAINT FK_1302F051F42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_result DROP FOREIGN KEY FK_1302F05167433D9C');
        $this->addSql('ALTER TABLE meeting_result DROP FOREIGN KEY FK_1302F051F42D3895');
        $this->addSql('DROP TABLE meeting_result');
    }
}
