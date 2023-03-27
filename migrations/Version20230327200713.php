<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327200713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, description VARCHAR(500) DEFAULT NULL, contenu MEDIUMTEXT DEFAULT NULL, date_publication DATE DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, auteur VARCHAR(255) DEFAULT NULL, avg_rating DOUBLE PRECISION DEFAULT NULL, INDEX IDX_23A0E669F34925F (id_categorie_id), INDEX IDX_23A0E6679F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_ratings (id INT AUTO_INCREMENT NOT NULL, id_article_id INT DEFAULT NULL, id_user_id INT DEFAULT NULL, rating INT DEFAULT NULL, INDEX IDX_2364437ED71E064B (id_article_id), INDEX IDX_2364437E79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_article (id INT AUTO_INCREMENT NOT NULL, libelle_cat VARCHAR(255) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, id_livreur_id INT DEFAULT NULL, id_echange_id INT DEFAULT NULL, date_creation_livraison DATE DEFAULT NULL, etat_livraison VARCHAR(10) NOT NULL, adresse_livraison1 VARCHAR(255) DEFAULT NULL, adresse_livraison2 VARCHAR(255) DEFAULT NULL, date_terminer_livraison DATE DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_A60C9F1F5DEEE7D6 (id_livreur_id), INDEX IDX_A60C9F1FB6FBB8CF (id_echange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie_article (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6679F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE article_ratings ADD CONSTRAINT FK_2364437ED71E064B FOREIGN KEY (id_article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_ratings ADD CONSTRAINT FK_2364437E79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F5DEEE7D6 FOREIGN KEY (id_livreur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FB6FBB8CF FOREIGN KEY (id_echange_id) REFERENCES echange (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E669F34925F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6679F37AE5');
        $this->addSql('ALTER TABLE article_ratings DROP FOREIGN KEY FK_2364437ED71E064B');
        $this->addSql('ALTER TABLE article_ratings DROP FOREIGN KEY FK_2364437E79F37AE5');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F5DEEE7D6');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FB6FBB8CF');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_ratings');
        $this->addSql('DROP TABLE categorie_article');
        $this->addSql('DROP TABLE livraison');
    }
}
