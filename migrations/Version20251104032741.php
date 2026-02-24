<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104032741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_meeting_rank ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot CHANGE `rank` meeting_position INT NOT NULL');
        $this->addSql('CREATE INDEX idx_meeting_meeting_position ON meeting_snapshot (meeting_id, meeting_position)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_meeting_meeting_position ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot CHANGE meeting_position `rank` INT NOT NULL');
        $this->addSql('CREATE INDEX idx_meeting_rank ON meeting_snapshot (meeting_id, `rank`)');
    }
}
