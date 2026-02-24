<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923032746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meeting (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(16) NOT NULL, label VARCHAR(255) DEFAULT NULL, slug VARCHAR(160) DEFAULT NULL, opened_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', closed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting_participant (id INT AUTO_INCREMENT NOT NULL, meeting_id INT DEFAULT NULL, shooter_id INT DEFAULT NULL, position SMALLINT NOT NULL, present TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FBFF656467433D9C (meeting_id), INDEX IDX_FBFF6564F42D3895 (shooter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF656467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF6564F42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE pass DROP FOREIGN KEY FK_CE70D424613FECDF');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113EC545AE5');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113F42D3895');
        $this->addSql('ALTER TABLE session_ranking DROP FOREIGN KEY FK_9260BB8A613FECDF');
        $this->addSql('ALTER TABLE session_ranking DROP FOREIGN KEY FK_9260BB8AF42D3895');
        $this->addSql('DROP TABLE pass');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE session_ranking');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pass (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, order_nr INT NOT NULL, status VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', completed_at DATETIME NOT NULL, INDEX IDX_CE70D424613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, pass_id INT NOT NULL, shooter_id INT NOT NULL, target_numbers JSON NOT NULL, hit_count SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_136AC113F42D3895 (shooter_id), INDEX IDX_136AC113EC545AE5 (pass_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, status VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE session_ranking (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, shooter_id INT NOT NULL, total_hits INT NOT NULL, ranking_position INT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9260BB8AF42D3895 (shooter_id), INDEX IDX_9260BB8A613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pass ADD CONSTRAINT FK_CE70D424613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113EC545AE5 FOREIGN KEY (pass_id) REFERENCES pass (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113F42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE session_ranking ADD CONSTRAINT FK_9260BB8A613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE session_ranking ADD CONSTRAINT FK_9260BB8AF42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF656467433D9C');
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF6564F42D3895');
        $this->addSql('DROP TABLE meeting');
        $this->addSql('DROP TABLE meeting_participant');
    }
}
