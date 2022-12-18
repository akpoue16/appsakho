<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221126130026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diligence (id INT AUTO_INCREMENT NOT NULL, contentieux_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', motif VARCHAR(255) NOT NULL, debut_time TIME DEFAULT NULL, fin_time TIME DEFAULT NULL, observation LONGTEXT DEFAULT NULL, INDEX IDX_5D64E0604CC72460 (contentieux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_perso (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE diligence ADD CONSTRAINT FK_5D64E0604CC72460 FOREIGN KEY (contentieux_id) REFERENCES contentieux (id)');
        $this->addSql('ALTER TABLE personnel ADD type_perso_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personnel ADD CONSTRAINT FK_A6BCF3DEDFDEC7E5 FOREIGN KEY (type_perso_id) REFERENCES type_perso (id)');
        $this->addSql('CREATE INDEX IDX_A6BCF3DEDFDEC7E5 ON personnel (type_perso_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DEDFDEC7E5');
        $this->addSql('DROP TABLE diligence');
        $this->addSql('DROP TABLE type_perso');
        $this->addSql('DROP INDEX IDX_A6BCF3DEDFDEC7E5 ON personnel');
        $this->addSql('ALTER TABLE personnel DROP type_perso_id');
    }
}
