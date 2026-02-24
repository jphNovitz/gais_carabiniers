<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923033750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF656467433D9C');
        $this->addSql('ALTER TABLE meeting_participant CHANGE meeting_id meeting_id INT NOT NULL, CHANGE shooter_id shooter_id INT NOT NULL');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF656467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF656467433D9C');
        $this->addSql('ALTER TABLE meeting_participant CHANGE meeting_id meeting_id INT DEFAULT NULL, CHANGE shooter_id shooter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF656467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
