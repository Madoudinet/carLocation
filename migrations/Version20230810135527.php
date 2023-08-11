<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810135527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, vehicule_id INT DEFAULT NULL, date_heure_depart DATETIME NOT NULL, date_heure_fin DATETIME NOT NULL, prix_total INT NOT NULL, date_enregistrement DATETIME NOT NULL, UNIQUE INDEX UNIQ_6EEAA67D6A99F74A (membre_id), UNIQUE INDEX UNIQ_6EEAA67D4A4A3511 (vehicule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6A99F74A FOREIGN KEY (membre_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D4A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id)');
        $this->addSql('ALTER TABLE user DROP statut');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6A99F74A');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D4A4A3511');
        $this->addSql('DROP TABLE commande');
        $this->addSql('ALTER TABLE user ADD statut INT NOT NULL');
    }
}
