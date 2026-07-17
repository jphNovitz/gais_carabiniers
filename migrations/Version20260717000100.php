<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260717000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Split yearly standings by shooting category';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE year_snapshot ADD shooting_category VARCHAR(255) DEFAULT 'classic' NOT NULL");
        $this->addSql('DROP INDEX uniq_year_participant ON year_snapshot');
        $this->addSql('CREATE UNIQUE INDEX uniq_year_participant_category ON year_snapshot (year, participant_id, shooting_category)');
        $this->addSql('CREATE INDEX idx_year_category_position ON year_snapshot (year, shooting_category, year_position)');
        $this->addSql('DELETE FROM year_snapshot');
        $this->addSql("
            INSERT INTO year_snapshot (
                participant_id,
                last_meeting_id,
                year,
                shooting_category,
                year_position,
                year_prev_position,
                total_score,
                meeting_count,
                average_hits,
                club_name,
                computed_at
            )
            SELECT
                ms.participant_id,
                NULL,
                YEAR(m.date),
                ms.shooting_category,
                0,
                NULL,
                SUM(ms.score),
                COUNT(ms.id),
                SUM(ms.score) / COUNT(ms.id),
                MAX(ms.club_name),
                NOW()
            FROM meeting_snapshot ms
            INNER JOIN meeting m ON m.id = ms.meeting_id
            WHERE m.type = 'Tir du mois'
            GROUP BY YEAR(m.date), ms.participant_id, ms.shooting_category
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_year_category_position ON year_snapshot');
        $this->addSql('DROP INDEX uniq_year_participant_category ON year_snapshot');
        $this->addSql('DELETE FROM year_snapshot WHERE shooting_category <> \'classic\'');
        $this->addSql('CREATE UNIQUE INDEX uniq_year_participant ON year_snapshot (year, participant_id)');
        $this->addSql('ALTER TABLE year_snapshot DROP shooting_category');
    }
}
