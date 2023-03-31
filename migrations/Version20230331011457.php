<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230331011457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP INDEX UNIQ_1F1B251EB6FBB8CF, ADD INDEX IDX_1F1B251EB6FBB8CF (id_echange_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP INDEX IDX_1F1B251EB6FBB8CF, ADD UNIQUE INDEX UNIQ_1F1B251EB6FBB8CF (id_echange_id)');
    }
}
