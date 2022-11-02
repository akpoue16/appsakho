<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221102075520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contentieux ADD avocat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055ECEDBF2DB2 FOREIGN KEY (avocat_id) REFERENCES personnel (id)');
        $this->addSql('CREATE INDEX IDX_DD6055ECEDBF2DB2 ON contentieux (avocat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contentieux DROP FOREIGN KEY FK_DD6055ECEDBF2DB2');
        $this->addSql('DROP INDEX IDX_DD6055ECEDBF2DB2 ON contentieux');
        $this->addSql('ALTER TABLE contentieux DROP avocat_id');
    }
}
