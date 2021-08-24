<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210823132444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence DROP FOREIGN KEY FK_94D4687F8A3C7387');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687F8A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence DROP FOREIGN KEY FK_94D4687F8A3C7387');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687F8A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id) ON DELETE CASCADE');
    }
}
