<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902210136 extends AbstractMigration
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
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, categorie_id_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_94D4687F8A3C7387 (categorie_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, INDEX IDX_D8698A7676C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, user_id INT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, descriptif LONGTEXT NOT NULL, INDEX IDX_590C103C54C8C93 (type_id), INDEX IDX_590C103A76ED395 (user_id), INDEX IDX_590C103A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_competence (experience_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_7B75D7C346E90E27 (experience_id), INDEX IDX_7B75D7C315761DAB (competence_id), PRIMARY KEY(experience_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission_en_cours (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT NOT NULL, employe_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_E86C8614A4AEAFEA (entreprise_id), UNIQUE INDEX UNIQ_E86C86141B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo_profil (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B369C5BFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, request_user_id INT DEFAULT NULL, pending TINYINT(1) NOT NULL, is_accepted TINYINT(1) NOT NULL, is_deny TINYINT(1) NOT NULL, INDEX IDX_3AB9D5C4A76ED395 (user_id), INDEX IDX_3AB9D5C48D4AA1C2 (request_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_mission (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, filename_id INT DEFAULT NULL, adresse_id INT DEFAULT NULL, mission_en_cours_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(120) NOT NULL, date_de_naissance DATE NOT NULL, telephone VARCHAR(35) NOT NULL, is_completed TINYINT(1) NOT NULL, is_accepted TINYINT(1) NOT NULL, is_available TINYINT(1) NOT NULL, anniversary_date DATE NOT NULL, modif_date DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64930335DEA (filename_id), UNIQUE INDEX UNIQ_8D93D6494DE7DC5C (adresse_id), UNIQUE INDEX UNIQ_8D93D64954B3DCCA (mission_en_cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_has_competence (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, competence_id INT DEFAULT NULL, maitrise VARCHAR(255) NOT NULL, is_liked TINYINT(1) NOT NULL, INDEX IDX_19134EB5A76ED395 (user_id), INDEX IDX_19134EB515761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687F8A3C7387 FOREIGN KEY (categorie_id_id) REFERENCES categorie (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7676C50E4A FOREIGN KEY (proprietaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103C54C8C93 FOREIGN KEY (type_id) REFERENCES type_mission (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE experience_competence ADD CONSTRAINT FK_7B75D7C346E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_competence ADD CONSTRAINT FK_7B75D7C315761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_en_cours ADD CONSTRAINT FK_E86C8614A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE mission_en_cours ADD CONSTRAINT FK_E86C86141B65292 FOREIGN KEY (employe_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photo_profil ADD CONSTRAINT FK_B369C5BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C48D4AA1C2 FOREIGN KEY (request_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64930335DEA FOREIGN KEY (filename_id) REFERENCES photo_profil (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adress (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64954B3DCCA FOREIGN KEY (mission_en_cours_id) REFERENCES mission_en_cours (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_competence ADD CONSTRAINT FK_19134EB5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_has_competence ADD CONSTRAINT FK_19134EB515761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DE7DC5C');
        $this->addSql('ALTER TABLE competence DROP FOREIGN KEY FK_94D4687F8A3C7387');
        $this->addSql('ALTER TABLE experience_competence DROP FOREIGN KEY FK_7B75D7C315761DAB');
        $this->addSql('ALTER TABLE user_has_competence DROP FOREIGN KEY FK_19134EB515761DAB');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103A4AEAFEA');
        $this->addSql('ALTER TABLE mission_en_cours DROP FOREIGN KEY FK_E86C8614A4AEAFEA');
        $this->addSql('ALTER TABLE experience_competence DROP FOREIGN KEY FK_7B75D7C346E90E27');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64954B3DCCA');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64930335DEA');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103C54C8C93');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7676C50E4A');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103A76ED395');
        $this->addSql('ALTER TABLE mission_en_cours DROP FOREIGN KEY FK_E86C86141B65292');
        $this->addSql('ALTER TABLE photo_profil DROP FOREIGN KEY FK_B369C5BFA76ED395');
        $this->addSql('ALTER TABLE relation_user DROP FOREIGN KEY FK_3AB9D5C4A76ED395');
        $this->addSql('ALTER TABLE relation_user DROP FOREIGN KEY FK_3AB9D5C48D4AA1C2');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE user_has_competence DROP FOREIGN KEY FK_19134EB5A76ED395');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE adress_temp');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experience_competence');
        $this->addSql('DROP TABLE mission_en_cours');
        $this->addSql('DROP TABLE photo_profil');
        $this->addSql('DROP TABLE relation_user');
        $this->addSql('DROP TABLE type_mission');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE user_has_competence');
    }
}
