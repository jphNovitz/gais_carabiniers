<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251105020208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_snapshot DROP FOREIGN KEY FK_BFDC38E0F42D3895');
        $this->addSql('DROP INDEX IDX_BFDC38E0F42D3895 ON meeting_snapshot');
        $this->addSql('DROP INDEX uniq_meeting_shooter ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot CHANGE shooter_id participant_id INT NOT NULL');
        $this->addSql('ALTER TABLE meeting_snapshot ADD CONSTRAINT FK_BFDC38E09D1C3019 FOREIGN KEY (participant_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BFDC38E09D1C3019 ON meeting_snapshot (participant_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_meeting_shooter ON meeting_snapshot (meeting_id, participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_snapshot DROP FOREIGN KEY FK_BFDC38E09D1C3019');
        $this->addSql('DROP INDEX IDX_BFDC38E09D1C3019 ON meeting_snapshot');
        $this->addSql('DROP INDEX uniq_meeting_shooter ON meeting_snapshot');
        $this->addSql('ALTER TABLE meeting_snapshot CHANGE participant_id shooter_id INT NOT NULL');
        $this->addSql('ALTER TABLE meeting_snapshot ADD CONSTRAINT FK_BFDC38E0F42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_BFDC38E0F42D3895 ON meeting_snapshot (shooter_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_meeting_shooter ON meeting_snapshot (meeting_id, shooter_id)');
    }
}
