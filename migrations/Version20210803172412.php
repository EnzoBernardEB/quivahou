<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803172412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE adresse_id adresse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adress (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494DE7DC5C ON user (adresse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DE7DC5C');
        $this->addSql('DROP INDEX UNIQ_8D93D6494DE7DC5C ON user');
        $this->addSql('ALTER TABLE user CHANGE adresse_id adresse_id INT NOT NULL');
    }
}
