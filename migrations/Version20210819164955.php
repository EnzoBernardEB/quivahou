<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210819164955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE relation_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, request_user_id INT DEFAULT NULL, pending TINYINT(1) NOT NULL, is_accepted TINYINT(1) NOT NULL, INDEX IDX_3AB9D5C4A76ED395 (user_id), INDEX IDX_3AB9D5C48D4AA1C2 (request_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C48D4AA1C2 FOREIGN KEY (request_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE relation_user');
    }
}
