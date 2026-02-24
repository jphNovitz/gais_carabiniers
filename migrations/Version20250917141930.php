<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250917141930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, pass_id INT NOT NULL, shooter_id INT NOT NULL, target_numbers JSON NOT NULL, hit_count SMALLINT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_136AC113EC545AE5 (pass_id), INDEX IDX_136AC113F42D3895 (shooter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE result ADD CONSTRAINT FK_136AC113EC545AE5 FOREIGN KEY (pass_id) REFERENCES pass (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE result ADD CONSTRAINT FK_136AC113F42D3895 FOREIGN KEY (shooter_id) REFERENCES `member` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE result DROP FOREIGN KEY FK_136AC113EC545AE5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE result DROP FOREIGN KEY FK_136AC113F42D3895
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE result
        SQL);
    }
}
