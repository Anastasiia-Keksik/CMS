<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200831104221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lancuszek (id INT AUTO_INCREMENT NOT NULL, osoba_polecajaca_id INT NOT NULL, osoba_polecona_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C6C2260E8CD2B617 (osoba_polecajaca_id), INDEX IDX_C6C2260EEF5FE62F (osoba_polecona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lancuszek ADD CONSTRAINT FK_C6C2260E8CD2B617 FOREIGN KEY (osoba_polecajaca_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE lancuszek ADD CONSTRAINT FK_C6C2260EEF5FE62F FOREIGN KEY (osoba_polecona_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_private_forum DROP INDEX UNIQ_47EC4F6284A66610, ADD INDEX IDX_47EC4F6284A66610 (user_admin_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lancuszek');
        $this->addSql('ALTER TABLE user_private_forum DROP INDEX IDX_47EC4F6284A66610, ADD UNIQUE INDEX UNIQ_47EC4F6284A66610 (user_admin_id)');
    }
}
