<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221217143618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience ADD audience_pre_id INT DEFAULT NULL, DROP motif, DROP renvoyer');
        $this->addSql('ALTER TABLE audience ADD CONSTRAINT FK_FDCD9418E27687B1 FOREIGN KEY (audience_pre_id) REFERENCES audience (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FDCD9418E27687B1 ON audience (audience_pre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audience DROP FOREIGN KEY FK_FDCD9418E27687B1');
        $this->addSql('DROP INDEX UNIQ_FDCD9418E27687B1 ON audience');
        $this->addSql('ALTER TABLE audience ADD motif LONGTEXT DEFAULT NULL, ADD renvoyer DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP audience_pre_id');
    }
}
