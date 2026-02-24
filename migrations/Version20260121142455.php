<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260121142455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_meeting_meeting_position ON meeting_snapshot');
        $this->addSql('DROP INDEX idx_points ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot ADD meeting_prev_position INT NOT NULL, DROP points, CHANGE meeting_count meeting_count INT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE INDEX idx_year_position ON meeting_snapshot (year, meeting_position)');
        $this->addSql('CREATE INDEX idx_year_score ON meeting_snapshot (year, total_score)');
        $this->addSql('CREATE INDEX idx_participant_year ON meeting_snapshot (participant_id, year)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_year_position ON meeting_snapshot');
        $this->addSql('DROP INDEX idx_year_score ON meeting_snapshot');
        $this->addSql('DROP INDEX idx_participant_year ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot ADD points DOUBLE PRECISION DEFAULT NULL, DROP meeting_prev_position, CHANGE meeting_count meeting_count INT DEFAULT 1 NOT NULL');
        $this->addSql('CREATE INDEX idx_meeting_meeting_position ON meeting_snapshot (meeting_id, meeting_position)');
        $this->addSql('CREATE INDEX idx_points ON meeting_snapshot (year, points)');
    }
}
