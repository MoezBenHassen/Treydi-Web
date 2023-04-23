<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423232653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authors ADD auteur_id INT DEFAULT NULL, ADD image_name VARCHAR(255) DEFAULT NULL, ADD image_size INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE authors ADD CONSTRAINT FK_8E0C2A5160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES authors (id)');
        $this->addSql('CREATE INDEX IDX_8E0C2A5160BB6FE6 ON authors (auteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authors DROP FOREIGN KEY FK_8E0C2A5160BB6FE6');
        $this->addSql('DROP INDEX IDX_8E0C2A5160BB6FE6 ON authors');
        $this->addSql('ALTER TABLE authors DROP auteur_id, DROP image_name, DROP image_size, DROP updated_at');
    }
}
