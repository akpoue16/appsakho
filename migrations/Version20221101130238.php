<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101130238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience ADD avocat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE audience ADD CONSTRAINT FK_FDCD9418EDBF2DB2 FOREIGN KEY (avocat_id) REFERENCES personnel (id)');
        $this->addSql('CREATE INDEX IDX_FDCD9418EDBF2DB2 ON audience (avocat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience DROP FOREIGN KEY FK_FDCD9418EDBF2DB2');
        $this->addSql('DROP INDEX IDX_FDCD9418EDBF2DB2 ON audience');
        $this->addSql('ALTER TABLE audience DROP avocat_id');
    }
}
