<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308123909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enchere (id INT AUTO_INCREMENT NOT NULL, leproduit_id INT DEFAULT NULL, letypeenchere_id INT DEFAULT NULL, datedebut DATETIME NOT NULL, datefin DATETIME NOT NULL, prixreserve DOUBLE PRECISION NOT NULL, visibilite TINYINT(1) NOT NULL, INDEX IDX_38D1870F9BB0CB11 (leproduit_id), INDEX IDX_38D1870FC38B1943 (letypeenchere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE encherir (id INT AUTO_INCREMENT NOT NULL, laenchere_id INT DEFAULT NULL, leuser_id INT DEFAULT NULL, prixenchere DOUBLE PRECISION NOT NULL, dateenchere DATETIME NOT NULL, INDEX IDX_503B7C87CC56A893 (laenchere_id), INDEX IDX_503B7C87FE57C42A (leuser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, codepostal VARCHAR(255) NOT NULL, portable VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin_produit (magasin_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_5E1A357B20096AE3 (magasin_id), INDEX IDX_5E1A357BF347EFB (produit_id), PRIMARY KEY(magasin_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, prixreel DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_enchere (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, photo VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_38D1870F9BB0CB11 FOREIGN KEY (leproduit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE enchere ADD CONSTRAINT FK_38D1870FC38B1943 FOREIGN KEY (letypeenchere_id) REFERENCES type_enchere (id)');
        $this->addSql('ALTER TABLE encherir ADD CONSTRAINT FK_503B7C87CC56A893 FOREIGN KEY (laenchere_id) REFERENCES enchere (id)');
        $this->addSql('ALTER TABLE encherir ADD CONSTRAINT FK_503B7C87FE57C42A FOREIGN KEY (leuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE magasin_produit ADD CONSTRAINT FK_5E1A357B20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magasin_produit ADD CONSTRAINT FK_5E1A357BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE encherir DROP FOREIGN KEY FK_503B7C87CC56A893');
        $this->addSql('ALTER TABLE magasin_produit DROP FOREIGN KEY FK_5E1A357B20096AE3');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_38D1870F9BB0CB11');
        $this->addSql('ALTER TABLE magasin_produit DROP FOREIGN KEY FK_5E1A357BF347EFB');
        $this->addSql('ALTER TABLE enchere DROP FOREIGN KEY FK_38D1870FC38B1943');
        $this->addSql('ALTER TABLE encherir DROP FOREIGN KEY FK_503B7C87FE57C42A');
        $this->addSql('DROP TABLE enchere');
        $this->addSql('DROP TABLE encherir');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE magasin_produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE type_enchere');
        $this->addSql('DROP TABLE user');
    }
}
