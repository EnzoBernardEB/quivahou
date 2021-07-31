<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210731182116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress ADD adresse VARCHAR(255) NOT NULL, DROP id_fantoir, DROP numero, DROP rep, DROP nom_voie, DROP code_postal, DROP code_insee, DROP nom_commune, DROP code_insee_ancienne_commune, DROP nom_ancienne_commune, DROP x, DROP y, DROP lon, DROP lat, DROP alias, DROP nom_ld, DROP libelle_acheminement, DROP nom_afnor, DROP source_position, DROP source_nom_voie');
        $this->addSql('ALTER TABLE user CHANGE photo_profil_id photo_profil_id INT NOT NULL, CHANGE filename_id filename_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress ADD id_fantoir VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD numero INT NOT NULL, ADD rep VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD code_postal INT NOT NULL, ADD code_insee INT NOT NULL, ADD nom_commune VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD code_insee_ancienne_commune VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD nom_ancienne_commune VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD x DOUBLE PRECISION NOT NULL, ADD y DOUBLE PRECISION NOT NULL, ADD lon DOUBLE PRECISION NOT NULL, ADD lat DOUBLE PRECISION NOT NULL, ADD alias VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD nom_ld VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD libelle_acheminement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD nom_afnor VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD source_position VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD source_nom_voie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adresse nom_voie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE photo_profil_id photo_profil_id INT DEFAULT NULL, CHANGE filename_id filename_id INT DEFAULT NULL');
    }
}
