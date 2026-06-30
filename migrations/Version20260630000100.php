<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260630000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Freeze shooting category on meeting participants and meeting snapshots';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE meeting_participant ADD shooting_category VARCHAR(255) DEFAULT 'classic' NOT NULL");
        $this->addSql("UPDATE meeting_participant mp INNER JOIN `member` m ON m.id = mp.shooter_id SET mp.shooting_category = CASE WHEN m.uses_support = 1 THEN 'supported' ELSE 'classic' END");
        $this->addSql("ALTER TABLE meeting_snapshot ADD shooting_category VARCHAR(255) DEFAULT 'classic' NOT NULL");
        $this->addSql("UPDATE meeting_snapshot ms INNER JOIN `member` m ON m.id = ms.participant_id SET ms.shooting_category = CASE WHEN m.uses_support = 1 THEN 'supported' ELSE 'classic' END");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meeting_participant DROP shooting_category');
        $this->addSql('ALTER TABLE meeting_snapshot DROP shooting_category');
    }
}
