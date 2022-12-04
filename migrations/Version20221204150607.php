<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221204150607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DE57889920');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP INDEX IDX_A6BCF3DE57889920 ON personnel');
        $this->addSql('ALTER TABLE personnel DROP fonction_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE personnel ADD fonction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnel ADD CONSTRAINT FK_A6BCF3DE57889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A6BCF3DE57889920 ON personnel (fonction_id)');
    }
}
