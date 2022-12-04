<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203180554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contentieux ADD qualite_ad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055ECFBE4EB1F FOREIGN KEY (qualite_ad_id) REFERENCES qualite_ad (id)');
        $this->addSql('CREATE INDEX IDX_DD6055ECFBE4EB1F ON contentieux (qualite_ad_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contentieux DROP FOREIGN KEY FK_DD6055ECFBE4EB1F');
        $this->addSql('DROP INDEX IDX_DD6055ECFBE4EB1F ON contentieux');
        $this->addSql('ALTER TABLE contentieux DROP qualite_ad_id');
    }
}
