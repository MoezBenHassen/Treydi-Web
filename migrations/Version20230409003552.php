<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230409003552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE echange_proposer (id INT AUTO_INCREMENT NOT NULL, id_echange_id INT NOT NULL, id_user_id INT NOT NULL, date_proposer DATE DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, INDEX IDX_F60C68D8B6FBB8CF (id_echange_id), INDEX IDX_F60C68D879F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE echange_proposer ADD CONSTRAINT FK_F60C68D8B6FBB8CF FOREIGN KEY (id_echange_id) REFERENCES echange (id)');
        $this->addSql('ALTER TABLE echange_proposer ADD CONSTRAINT FK_F60C68D879F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE item DROP INDEX UNIQ_1F1B251EB6FBB8CF, ADD INDEX IDX_1F1B251EB6FBB8CF (id_echange_id)');
        $this->addSql('ALTER TABLE utilisateur ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP role, CHANGE password password VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE echange_proposer DROP FOREIGN KEY FK_F60C68D8B6FBB8CF');
        $this->addSql('ALTER TABLE echange_proposer DROP FOREIGN KEY FK_F60C68D879F37AE5');
        $this->addSql('DROP TABLE echange_proposer');
        $this->addSql('ALTER TABLE item DROP INDEX IDX_1F1B251EB6FBB8CF, ADD UNIQUE INDEX UNIQ_1F1B251EB6FBB8CF (id_echange_id)');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD role VARCHAR(255) DEFAULT NULL, DROP roles, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL');
    }
}
