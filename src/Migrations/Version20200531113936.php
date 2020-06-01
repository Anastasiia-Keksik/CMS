<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200531113936 extends AbstractMigration
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
        $this->addSql('ALTER TABLE forum_category CHANGE is_it_user_private_forum_id is_it_user_private_forum_id INT DEFAULT NULL, CHANGE hidden hidden TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_forum CHANGE category_id category_id INT DEFAULT NULL, CHANGE posts posts INT DEFAULT NULL, CHANGE topics topics INT DEFAULT NULL, CHANGE password password VARCHAR(32) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AE3F3737');
        $this->addSql('DROP INDEX IDX_996BCC5AE3F3737 ON forum_post');
        $this->addSql('ALTER TABLE forum_post CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE soft_delete soft_delete TINYINT(1) DEFAULT NULL, CHANGE forum_forum_id forum_topic_id INT NOT NULL');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5A38A6ADDA FOREIGN KEY (forum_topic_id) REFERENCES forum_topic (id)');
        $this->addSql('CREATE INDEX IDX_996BCC5A38A6ADDA ON forum_post (forum_topic_id)');
        $this->addSql('ALTER TABLE forum_post_conversation CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE soft_delete soft_delete TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_private_forum CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE password password VARCHAR(64) DEFAULT NULL, CHANGE soft_delete soft_delete TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE first_name first_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE paypal paypal VARCHAR(160) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE twitter twitter VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE facebook facebook VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE last_online last_online DATETIME DEFAULT \'NULL\', CHANGE country country VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE linkedin linkedin VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE reddit reddit VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE skype skype VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE flickr flickr VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE instagram instagram VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE youtube youtube VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\', CHANGE template template VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_category CHANGE is_it_user_private_forum_id is_it_user_private_forum_id INT DEFAULT NULL, CHANGE hidden hidden TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE forum_forum CHANGE category_id category_id INT DEFAULT NULL, CHANGE posts posts INT DEFAULT NULL, CHANGE topics topics INT DEFAULT NULL, CHANGE password password VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5A38A6ADDA');
        $this->addSql('DROP INDEX IDX_996BCC5A38A6ADDA ON forum_post');
        $this->addSql('ALTER TABLE forum_post CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE soft_delete soft_delete TINYINT(1) DEFAULT \'NULL\', CHANGE forum_topic_id forum_forum_id INT NOT NULL');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AE3F3737 FOREIGN KEY (forum_forum_id) REFERENCES forum_forum (id)');
        $this->addSql('CREATE INDEX IDX_996BCC5AE3F3737 ON forum_post (forum_forum_id)');
        $this->addSql('ALTER TABLE forum_post_conversation CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE soft_delete soft_delete TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_private_forum CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE password password VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE soft_delete soft_delete TINYINT(1) DEFAULT \'NULL\'');
    }
}
