<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220618211818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adversaire (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, raison_socieal VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tel VARCHAR(255) DEFAULT NULL, cel VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contentieux (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, qualite_id INT DEFAULT NULL, confrere_id INT DEFAULT NULL, juridiction_id INT DEFAULT NULL, nature_id INT DEFAULT NULL, adversaire_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', objet LONGTEXT DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, INDEX IDX_DD6055EC19EB6921 (client_id), INDEX IDX_DD6055ECA6338570 (qualite_id), INDEX IDX_DD6055EC355C1A75 (confrere_id), INDEX IDX_DD6055ECD38DB6BD (juridiction_id), INDEX IDX_DD6055EC3BCB2E4B (nature_id), INDEX IDX_DD6055EC3E4689F5 (adversaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055EC19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055ECA6338570 FOREIGN KEY (qualite_id) REFERENCES qualite (id)');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055EC355C1A75 FOREIGN KEY (confrere_id) REFERENCES confrere (id)');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055ECD38DB6BD FOREIGN KEY (juridiction_id) REFERENCES juridiction (id)');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055EC3BCB2E4B FOREIGN KEY (nature_id) REFERENCES nature (id)');
        $this->addSql('ALTER TABLE contentieux ADD CONSTRAINT FK_DD6055EC3E4689F5 FOREIGN KEY (adversaire_id) REFERENCES adversaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contentieux DROP FOREIGN KEY FK_DD6055EC3E4689F5');
        $this->addSql('DROP TABLE adversaire');
        $this->addSql('DROP TABLE contentieux');
    }
}
