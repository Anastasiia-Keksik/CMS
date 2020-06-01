<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526185444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(120) DEFAULT NULL, last_name VARCHAR(120) DEFAULT NULL, email VARCHAR(160) NOT NULL, paypal VARCHAR(160) DEFAULT NULL, twitter VARCHAR(15) DEFAULT NULL, facebook VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, last_online DATETIME DEFAULT NULL, country VARCHAR(64) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, linkedin VARCHAR(64) DEFAULT NULL, reddit VARCHAR(64) DEFAULT NULL, skype VARCHAR(255) DEFAULT NULL, flickr VARCHAR(64) DEFAULT NULL, instagram VARCHAR(64) DEFAULT NULL, youtube VARCHAR(32) DEFAULT NULL, newsletter TINYINT(1) DEFAULT NULL, template VARCHAR(32) DEFAULT NULL, agreed_terms_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_7D3656A4F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_category (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_98B9EFCAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_child (id INT AUTO_INCREMENT NOT NULL, main_menu_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, url_value VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_8BE7032D91223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_category (id INT AUTO_INCREMENT NOT NULL, main_menu_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_A4D7D44091223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_child (id INT AUTO_INCREMENT NOT NULL, main_menu_sub_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, url_value VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_59BB1CEEEB380F38 (main_menu_sub_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passwords (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_ED822B16A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE main_menu_category ADD CONSTRAINT FK_98B9EFCAA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE main_menu_child ADD CONSTRAINT FK_8BE7032D91223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_category ADD CONSTRAINT FK_A4D7D44091223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_child ADD CONSTRAINT FK_59BB1CEEEB380F38 FOREIGN KEY (main_menu_sub_category_id) REFERENCES main_menu_sub_category (id)');
        $this->addSql('ALTER TABLE passwords ADD CONSTRAINT FK_ED822B16A76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_menu_category DROP FOREIGN KEY FK_98B9EFCAA76ED395');
        $this->addSql('ALTER TABLE passwords DROP FOREIGN KEY FK_ED822B16A76ED395');
        $this->addSql('ALTER TABLE main_menu_child DROP FOREIGN KEY FK_8BE7032D91223235');
        $this->addSql('ALTER TABLE main_menu_sub_category DROP FOREIGN KEY FK_A4D7D44091223235');
        $this->addSql('ALTER TABLE main_menu_sub_child DROP FOREIGN KEY FK_59BB1CEEEB380F38');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE main_menu_category');
        $this->addSql('DROP TABLE main_menu_child');
        $this->addSql('DROP TABLE main_menu_sub_category');
        $this->addSql('DROP TABLE main_menu_sub_child');
        $this->addSql('DROP TABLE passwords');
    }
}
