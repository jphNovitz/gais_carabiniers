<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260310004322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round_shot DROP FOREIGN KEY FK_EB0991B1C17D658');
        $this->addSql('ALTER TABLE round_shot CHANGE meeting_participant_id meeting_participant_id INT NOT NULL');
        $this->addSql('ALTER TABLE round_shot ADD CONSTRAINT FK_EB0991B1C17D658 FOREIGN KEY (meeting_participant_id) REFERENCES meeting_participant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round_shot DROP FOREIGN KEY FK_EB0991B1C17D658');
        $this->addSql('ALTER TABLE round_shot CHANGE meeting_participant_id meeting_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE round_shot ADD CONSTRAINT FK_EB0991B1C17D658 FOREIGN KEY (meeting_participant_id) REFERENCES meeting_participant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
