<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200215230126 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE menu_detaille (id INT AUTO_INCREMENT NOT NULL, id_menu_id INT DEFAULT NULL, id_photos_id INT NOT NULL, presentation_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description_courte VARCHAR(255) NOT NULL, description_longue LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, date_enregistrement DATETIME NOT NULL, nbr_personnes INT NOT NULL, duree_prepare INT NOT NULL, temperature_min INT DEFAULT NULL, temperature_max INT DEFAULT NULL, prix_unit DOUBLE PRECISION NOT NULL, ingredients LONGTEXT NOT NULL, suggestions LONGTEXT DEFAULT NULL, INDEX IDX_3CE7D67124A4062 (id_menu_id), UNIQUE INDEX UNIQ_3CE7D6783345045 (id_photos_id), INDEX IDX_3CE7D67AB627E8B (presentation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description_courte VARCHAR(255) NOT NULL, description_longue LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, date_enregistrement DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, photo_1 VARCHAR(255) NOT NULL, photo_2 VARCHAR(255) NOT NULL, photo_3 VARCHAR(255) DEFAULT NULL, photo_4 VARCHAR(255) DEFAULT NULL, photo_5 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presentation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(20) DEFAULT NULL, nom VARCHAR(20) DEFAULT NULL, prenom VARCHAR(20) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, civilite VARCHAR(2) NOT NULL, date_enregistrement DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_detaille ADD CONSTRAINT FK_3CE7D67124A4062 FOREIGN KEY (id_menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_detaille ADD CONSTRAINT FK_3CE7D6783345045 FOREIGN KEY (id_photos_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE menu_detaille ADD CONSTRAINT FK_3CE7D67AB627E8B FOREIGN KEY (presentation_id) REFERENCES presentation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu_detaille DROP FOREIGN KEY FK_3CE7D67124A4062');
        $this->addSql('ALTER TABLE menu_detaille DROP FOREIGN KEY FK_3CE7D6783345045');
        $this->addSql('ALTER TABLE menu_detaille DROP FOREIGN KEY FK_3CE7D67AB627E8B');
        $this->addSql('DROP TABLE menu_detaille');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE presentation');
        $this->addSql('DROP TABLE user');
    }
}
