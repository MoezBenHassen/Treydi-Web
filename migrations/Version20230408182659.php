<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408182659 extends AbstractMigration
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
        $this->addSql('CREATE TABLE categorie_coupon (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) DEFAULT NULL, description_categorie VARCHAR(255) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_items (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(255) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_categorie_id INT DEFAULT NULL, titre_coupon VARCHAR(255) DEFAULT NULL, description_coupon VARCHAR(255) DEFAULT NULL, date_expiration DATE DEFAULT NULL, etat_coupon VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_64BF3F0279F37AE5 (id_user_id), INDEX IDX_64BF3F029F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE echange (id INT AUTO_INCREMENT NOT NULL, id_user1_id INT DEFAULT NULL, id_user2_id INT DEFAULT NULL, date_echange DATE DEFAULT NULL, liv_etat VARCHAR(255) DEFAULT NULL, titre_echange VARCHAR(255) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_B577E3BF675C81E (id_user1_id), INDEX IDX_B577E3BF14C067F0 (id_user2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE echange_proposer (id INT AUTO_INCREMENT NOT NULL, id_echange_id INT NOT NULL, id_user_id INT NOT NULL, date_proposer DATE DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_F60C68D8B6FBB8CF (id_echange_id), INDEX IDX_F60C68D879F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_categorie_id INT DEFAULT NULL, id_echange_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, etat VARCHAR(10) DEFAULT NULL, type VARCHAR(10) DEFAULT NULL, imageurl VARCHAR(255) DEFAULT NULL, likes INT DEFAULT NULL, dislikes INT DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_1F1B251E79F37AE5 (id_user_id), INDEX IDX_1F1B251E9F34925F (id_categorie_id), INDEX IDX_1F1B251EB6FBB8CF (id_echange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE like_items (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, id_livreur_id INT DEFAULT NULL, id_echange_id INT DEFAULT NULL, date_creation_livraison DATE DEFAULT NULL, etat_livraison VARCHAR(10) NOT NULL, adresse_livraison1 VARCHAR(255) DEFAULT NULL, adresse_livraison2 VARCHAR(255) DEFAULT NULL, date_terminer_livraison DATE DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_A60C9F1F5DEEE7D6 (id_livreur_id), INDEX IDX_A60C9F1FB6FBB8CF (id_echange_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, titre_reclamation VARCHAR(255) DEFAULT NULL, description_reclamation VARCHAR(500) DEFAULT NULL, etat_reclamation VARCHAR(10) DEFAULT NULL, date_creation DATE DEFAULT NULL, date_cloture DATE DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_CE60640479F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_reclamation_id INT DEFAULT NULL, titre_reponse VARCHAR(255) DEFAULT NULL, description_reponse VARCHAR(255) DEFAULT NULL, date_reponse DATE DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_5FB6DEC7100D1FDF (id_reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, avatar_url VARCHAR(255) DEFAULT NULL, score INT DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, date_naissance DATE DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie_article (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6679F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE article_ratings ADD CONSTRAINT FK_2364437ED71E064B FOREIGN KEY (id_article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_ratings ADD CONSTRAINT FK_2364437E79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F0279F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F029F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie_coupon (id)');
        $this->addSql('ALTER TABLE echange ADD CONSTRAINT FK_B577E3BF675C81E FOREIGN KEY (id_user1_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE echange ADD CONSTRAINT FK_B577E3BF14C067F0 FOREIGN KEY (id_user2_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE echange_proposer ADD CONSTRAINT FK_F60C68D8B6FBB8CF FOREIGN KEY (id_echange_id) REFERENCES echange (id)');
        $this->addSql('ALTER TABLE echange_proposer ADD CONSTRAINT FK_F60C68D879F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E9F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie_items (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EB6FBB8CF FOREIGN KEY (id_echange_id) REFERENCES echange (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F5DEEE7D6 FOREIGN KEY (id_livreur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FB6FBB8CF FOREIGN KEY (id_echange_id) REFERENCES echange (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640479F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7100D1FDF FOREIGN KEY (id_reclamation_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E669F34925F');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6679F37AE5');
        $this->addSql('ALTER TABLE article_ratings DROP FOREIGN KEY FK_2364437ED71E064B');
        $this->addSql('ALTER TABLE article_ratings DROP FOREIGN KEY FK_2364437E79F37AE5');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F0279F37AE5');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F029F34925F');
        $this->addSql('ALTER TABLE echange DROP FOREIGN KEY FK_B577E3BF675C81E');
        $this->addSql('ALTER TABLE echange DROP FOREIGN KEY FK_B577E3BF14C067F0');
        $this->addSql('ALTER TABLE echange_proposer DROP FOREIGN KEY FK_F60C68D8B6FBB8CF');
        $this->addSql('ALTER TABLE echange_proposer DROP FOREIGN KEY FK_F60C68D879F37AE5');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E79F37AE5');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E9F34925F');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EB6FBB8CF');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F5DEEE7D6');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FB6FBB8CF');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640479F37AE5');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7100D1FDF');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_ratings');
        $this->addSql('DROP TABLE categorie_article');
        $this->addSql('DROP TABLE categorie_coupon');
        $this->addSql('DROP TABLE categorie_items');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE echange');
        $this->addSql('DROP TABLE echange_proposer');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE like_items');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
