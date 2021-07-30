<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210729204922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, id_fantoir VARCHAR(255) DEFAULT NULL, numero INT NOT NULL, rep VARCHAR(255) DEFAULT NULL, nom_voie VARCHAR(255) NOT NULL, code_postal INT NOT NULL, code_insee INT NOT NULL, nom_commune VARCHAR(255) NOT NULL, code_insee_ancienne_commune VARCHAR(255) DEFAULT NULL, nom_ancienne_commune VARCHAR(255) DEFAULT NULL, x DOUBLE PRECISION NOT NULL, y DOUBLE PRECISION NOT NULL, lon DOUBLE PRECISION NOT NULL, lat DOUBLE PRECISION NOT NULL, alias VARCHAR(255) DEFAULT NULL, nom_ld VARCHAR(255) DEFAULT NULL, libelle_acheminement VARCHAR(255) NOT NULL, nom_afnor VARCHAR(255) NOT NULL, source_position VARCHAR(255) NOT NULL, source_nom_voie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103A76ED395');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492AA08659 FOREIGN KEY (photo_profil_id) REFERENCES photo_profil (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64930335DEA FOREIGN KEY (filename_id) REFERENCES photo_profil (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6492AA08659 ON user (photo_profil_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64930335DEA ON user (filename_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, nom_de_rue VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ville VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, code_postal VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, pays VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE adress');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103A76ED395');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492AA08659');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64930335DEA');
        $this->addSql('DROP INDEX UNIQ_8D93D6492AA08659 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D64930335DEA ON user');
    }
}
