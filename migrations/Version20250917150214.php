<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250917150214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE session_ranking (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, shooter_id INT NOT NULL, total_hits INT NOT NULL, ranking_position INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_9260BB8A613FECDF (session_id), INDEX IDX_9260BB8AF42D3895 (shooter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session_ranking ADD CONSTRAINT FK_9260BB8A613FECDF FOREIGN KEY (session_id) REFERENCES session (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session_ranking ADD CONSTRAINT FK_9260BB8AF42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE session_ranking DROP FOREIGN KEY FK_9260BB8A613FECDF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE session_ranking DROP FOREIGN KEY FK_9260BB8AF42D3895
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE session_ranking
        SQL);
    }
}
