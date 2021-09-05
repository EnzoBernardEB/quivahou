<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210904122009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE relation_user DROP FOREIGN KEY FK_3AB9D5C48D4AA1C2');
        $this->addSql('ALTER TABLE relation_user DROP FOREIGN KEY FK_3AB9D5C4A76ED395');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C48D4AA1C2 FOREIGN KEY (request_user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DE7DC5C');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adress (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE relation_user DROP FOREIGN KEY FK_3AB9D5C4A76ED395');
        $this->addSql('ALTER TABLE relation_user DROP FOREIGN KEY FK_3AB9D5C48D4AA1C2');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation_user ADD CONSTRAINT FK_3AB9D5C48D4AA1C2 FOREIGN KEY (request_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494DE7DC5C');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adress (id) ON DELETE SET NULL');
    }
}
