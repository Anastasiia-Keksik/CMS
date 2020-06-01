<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200527145549 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account CHANGE roles roles JSON NOT NULL, CHANGE first_name first_name VARCHAR(120) DEFAULT NULL, CHANGE last_name last_name VARCHAR(120) DEFAULT NULL, CHANGE paypal paypal VARCHAR(160) DEFAULT NULL, CHANGE twitter twitter VARCHAR(15) DEFAULT NULL, CHANGE facebook facebook VARCHAR(50) DEFAULT NULL, CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE last_online last_online DATETIME DEFAULT NULL, CHANGE country country VARCHAR(64) DEFAULT NULL, CHANGE city city VARCHAR(64) DEFAULT NULL, CHANGE linkedin linkedin VARCHAR(64) DEFAULT NULL, CHANGE reddit reddit VARCHAR(64) DEFAULT NULL, CHANGE skype skype VARCHAR(255) DEFAULT NULL, CHANGE flickr flickr VARCHAR(64) DEFAULT NULL, CHANGE instagram instagram VARCHAR(64) DEFAULT NULL, CHANGE youtube youtube VARCHAR(32) DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL, CHANGE template template VARCHAR(32) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4E7927C74 ON account (email)');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_7D3656A4E7927C74 ON account');
        $this->addSql('ALTER TABLE account CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE first_name first_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE paypal paypal VARCHAR(160) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE twitter twitter VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE facebook facebook VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE last_online last_online DATETIME DEFAULT \'NULL\', CHANGE country country VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE linkedin linkedin VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE reddit reddit VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE skype skype VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE flickr flickr VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE instagram instagram VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE youtube youtube VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\', CHANGE template template VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
    }
}
