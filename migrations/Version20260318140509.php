<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318140509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE year_snapshot (id INT AUTO_INCREMENT NOT NULL, participant_id INT NOT NULL, last_meeting_id INT DEFAULT NULL, year INT NOT NULL, year_position INT DEFAULT 0 NOT NULL, year_prev_position INT DEFAULT NULL, total_score INT DEFAULT 0 NOT NULL, meeting_count INT DEFAULT 0 NOT NULL, average_hits NUMERIC(6, 2) DEFAULT \'0\' NOT NULL, club_name VARCHAR(255) DEFAULT NULL, computed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_58673509D1C3019 (participant_id), INDEX IDX_58673508E375776 (last_meeting_id), INDEX idx_year_position (year, year_position), INDEX idx_year_score (year, total_score), UNIQUE INDEX uniq_year_participant (year, participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE year_snapshot ADD CONSTRAINT FK_58673509D1C3019 FOREIGN KEY (participant_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE year_snapshot ADD CONSTRAINT FK_58673508E375776 FOREIGN KEY (last_meeting_id) REFERENCES meeting (id) ON DELETE SET NULL');
        $this->addSql('DROP INDEX idx_year_position ON meeting_snapshot');
        $this->addSql('DROP INDEX idx_year_score ON meeting_snapshot');
        $this->addSql('DROP INDEX idx_participant_year ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot DROP total_score, DROP shooter_name, DROP meeting_label, DROP year, DROP meeting_prev_position, DROP average_hits, CHANGE meeting_count score INT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE INDEX idx_meeting_position ON meeting_snapshot (meeting_id, meeting_position)');
        $this->addSql('ALTER TABLE meeting_snapshot RENAME INDEX uniq_meeting_shooter TO uniq_meeting_participant');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE year_snapshot DROP FOREIGN KEY FK_58673509D1C3019');
        $this->addSql('ALTER TABLE year_snapshot DROP FOREIGN KEY FK_58673508E375776');
        $this->addSql('DROP TABLE year_snapshot');
        $this->addSql('DROP INDEX idx_meeting_position ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot ADD total_score DOUBLE PRECISION NOT NULL, ADD shooter_name VARCHAR(255) NOT NULL, ADD meeting_label VARCHAR(255) NOT NULL, ADD year INT NOT NULL, ADD meeting_prev_position INT NOT NULL, ADD average_hits NUMERIC(6, 2) NOT NULL, CHANGE score meeting_count INT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE INDEX idx_year_position ON meeting_snapshot (year, meeting_position)');
        $this->addSql('CREATE INDEX idx_year_score ON meeting_snapshot (year, total_score)');
        $this->addSql('CREATE INDEX idx_participant_year ON meeting_snapshot (participant_id, year)');
        $this->addSql('ALTER TABLE meeting_snapshot RENAME INDEX uniq_meeting_participant TO uniq_meeting_shooter');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COLLATE `utf8mb4_bin`');
    }
}
