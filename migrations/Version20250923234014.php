<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923234014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX idx_meeting_date ON meeting (date)');
        $this->addSql('CREATE INDEX idx_meeting_date_status ON meeting (date, status)');
        $this->addSql('CREATE UNIQUE INDEX uniq_meeting_shooter ON meeting_participant (meeting_id, shooter_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_meeting_position ON meeting_participant (meeting_id, position)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_meeting_shooter ON meeting_participant');
        $this->addSql('DROP INDEX uniq_meeting_position ON meeting_participant');
        $this->addSql('DROP INDEX idx_meeting_date ON meeting');
        $this->addSql('DROP INDEX idx_meeting_date_status ON meeting');
    }
}
