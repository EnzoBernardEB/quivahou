<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725144725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, nom VARCHAR(255) NOT NULL, descriptif LONGTEXT NOT NULL, INDEX IDX_590C103C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_competence (experience_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_7B75D7C346E90E27 (experience_id), INDEX IDX_7B75D7C315761DAB (competence_id), PRIMARY KEY(experience_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_mission (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103C54C8C93 FOREIGN KEY (type_id) REFERENCES type_mission (id)');
        $this->addSql('ALTER TABLE experience_competence ADD CONSTRAINT FK_7B75D7C346E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_competence ADD CONSTRAINT FK_7B75D7C315761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experience_competence DROP FOREIGN KEY FK_7B75D7C346E90E27');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103C54C8C93');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experience_competence');
        $this->addSql('DROP TABLE type_mission');
    }
}
