<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200520150642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_category (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, INDEX IDX_98B9EFCAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_child (id INT AUTO_INCREMENT NOT NULL, main_menu_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, INDEX IDX_8BE7032D91223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_category (id INT AUTO_INCREMENT NOT NULL, main_menu_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, INDEX IDX_A4D7D44091223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_child (id INT AUTO_INCREMENT NOT NULL, main_menu_sub_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, INDEX IDX_59BB1CEEEB380F38 (main_menu_sub_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE main_menu_category ADD CONSTRAINT FK_98B9EFCAA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE main_menu_child ADD CONSTRAINT FK_8BE7032D91223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_category ADD CONSTRAINT FK_A4D7D44091223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_child ADD CONSTRAINT FK_59BB1CEEEB380F38 FOREIGN KEY (main_menu_sub_category_id) REFERENCES main_menu_sub_category (id)');
        $this->addSql('ALTER TABLE account CHANGE roles roles JSON NOT NULL, CHANGE first_name first_name VARCHAR(120) DEFAULT NULL, CHANGE last_name last_name VARCHAR(120) DEFAULT NULL, CHANGE paypal paypal VARCHAR(160) DEFAULT NULL, CHANGE twitter twitter VARCHAR(15) DEFAULT NULL, CHANGE facebook facebook VARCHAR(50) DEFAULT NULL, CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE last_online last_online DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_menu_child DROP FOREIGN KEY FK_8BE7032D91223235');
        $this->addSql('ALTER TABLE main_menu_sub_category DROP FOREIGN KEY FK_A4D7D44091223235');
        $this->addSql('ALTER TABLE main_menu_sub_child DROP FOREIGN KEY FK_59BB1CEEEB380F38');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE main_menu_category');
        $this->addSql('DROP TABLE main_menu_child');
        $this->addSql('DROP TABLE main_menu_sub_category');
        $this->addSql('DROP TABLE main_menu_sub_child');
        $this->addSql('ALTER TABLE account CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE first_name first_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE paypal paypal VARCHAR(160) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE twitter twitter VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE facebook facebook VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE last_online last_online DATETIME DEFAULT \'NULL\'');
    }
}
