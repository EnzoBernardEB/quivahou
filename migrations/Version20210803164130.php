<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803164130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, housenumber VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adress_temp (id INT AUTO_INCREMENT NOT NULL, housenumber VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adress (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494DE7DC5C ON user (adresse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DE7DC5C');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE adress_temp');
        $this->addSql('DROP INDEX UNIQ_8D93D6494DE7DC5C ON user');
    }
}
