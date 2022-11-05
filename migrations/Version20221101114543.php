<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101114543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience ADD contentieux_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE audience ADD CONSTRAINT FK_FDCD94184CC72460 FOREIGN KEY (contentieux_id) REFERENCES contentieux (id)');
        $this->addSql('CREATE INDEX IDX_FDCD94184CC72460 ON audience (contentieux_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience DROP FOREIGN KEY FK_FDCD94184CC72460');
        $this->addSql('DROP INDEX IDX_FDCD94184CC72460 ON audience');
        $this->addSql('ALTER TABLE audience DROP contentieux_id');
    }
}
