<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200605170057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_forum_category (id INT AUTO_INCREMENT NOT NULL, is_it_user_private_forum_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, order_value INT NOT NULL, hidden TINYINT(1) DEFAULT NULL, INDEX IDX_4A3A4B7F3A1E2C91 (is_it_user_private_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_forum (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, posts INT DEFAULT NULL, topics INT DEFAULT NULL, password VARCHAR(32) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, icon LONGBLOB DEFAULT NULL, INDEX IDX_3444D18F12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, forum_topic_id INT NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, likes INT NOT NULL, INDEX IDX_3E0C0AB6F675F31B (author_id), INDEX IDX_3E0C0AB638A6ADDA (forum_topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_post_conversation (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, post_id INT NOT NULL, content VARCHAR(255) NOT NULL, solf_delete TINYINT(1) NOT NULL, INDEX IDX_41349B88F675F31B (author_id), INDEX IDX_41349B884B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, forum_id INT NOT NULL, title VARCHAR(255) NOT NULL, soft_delete TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, last_post_at DATETIME NOT NULL, INDEX IDX_2C2FB159F675F31B (author_id), INDEX IDX_2C2FB15929CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_forum_category ADD CONSTRAINT FK_4A3A4B7F3A1E2C91 FOREIGN KEY (is_it_user_private_forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE user_forum_forum ADD CONSTRAINT FK_3444D18F12469DE2 FOREIGN KEY (category_id) REFERENCES user_forum_category (id)');
        $this->addSql('ALTER TABLE user_forum_post ADD CONSTRAINT FK_3E0C0AB6F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_post ADD CONSTRAINT FK_3E0C0AB638A6ADDA FOREIGN KEY (forum_topic_id) REFERENCES user_forum_topic (id)');
        $this->addSql('ALTER TABLE user_forum_post_conversation ADD CONSTRAINT FK_41349B88F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_post_conversation ADD CONSTRAINT FK_41349B884B89032C FOREIGN KEY (post_id) REFERENCES user_forum_post (id)');
        $this->addSql('ALTER TABLE user_forum_topic ADD CONSTRAINT FK_2C2FB159F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_topic ADD CONSTRAINT FK_2C2FB15929CCBAD0 FOREIGN KEY (forum_id) REFERENCES user_forum_forum (id)');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account CHANGE roles roles JSON NOT NULL, CHANGE first_name first_name VARCHAR(120) DEFAULT NULL, CHANGE last_name last_name VARCHAR(120) DEFAULT NULL, CHANGE paypal paypal VARCHAR(160) DEFAULT NULL, CHANGE twitter twitter VARCHAR(15) DEFAULT NULL, CHANGE facebook facebook VARCHAR(50) DEFAULT NULL, CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE last_online last_online DATETIME DEFAULT NULL, CHANGE country country VARCHAR(64) DEFAULT NULL, CHANGE city city VARCHAR(64) DEFAULT NULL, CHANGE linkedin linkedin VARCHAR(64) DEFAULT NULL, CHANGE reddit reddit VARCHAR(64) DEFAULT NULL, CHANGE skype skype VARCHAR(255) DEFAULT NULL, CHANGE flickr flickr VARCHAR(64) DEFAULT NULL, CHANGE instagram instagram VARCHAR(64) DEFAULT NULL, CHANGE youtube youtube VARCHAR(32) DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL, CHANGE template template VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_category CHANGE is_it_user_private_forum_id is_it_user_private_forum_id INT DEFAULT NULL, CHANGE hidden hidden TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_forum CHANGE category_id category_id INT DEFAULT NULL, CHANGE posts posts INT DEFAULT NULL, CHANGE topics topics INT DEFAULT NULL, CHANGE password password VARCHAR(32) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_post CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE soft_delete soft_delete TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_post_conversation CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE soft_delete soft_delete TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_private_forum CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE password password VARCHAR(64) DEFAULT NULL, CHANGE soft_delete soft_delete TINYINT(1) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_forum_forum DROP FOREIGN KEY FK_3444D18F12469DE2');
        $this->addSql('ALTER TABLE user_forum_topic DROP FOREIGN KEY FK_2C2FB15929CCBAD0');
        $this->addSql('ALTER TABLE user_forum_post_conversation DROP FOREIGN KEY FK_41349B884B89032C');
        $this->addSql('ALTER TABLE user_forum_post DROP FOREIGN KEY FK_3E0C0AB638A6ADDA');
        $this->addSql('DROP TABLE user_forum_category');
        $this->addSql('DROP TABLE user_forum_forum');
        $this->addSql('DROP TABLE user_forum_post');
        $this->addSql('DROP TABLE user_forum_post_conversation');
        $this->addSql('DROP TABLE user_forum_topic');
        $this->addSql('ALTER TABLE account CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE first_name first_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE paypal paypal VARCHAR(160) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE twitter twitter VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE facebook facebook VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE last_online last_online DATETIME DEFAULT \'NULL\', CHANGE country country VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE linkedin linkedin VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE reddit reddit VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE skype skype VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE flickr flickr VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE instagram instagram VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE youtube youtube VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\', CHANGE template template VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_category CHANGE is_it_user_private_forum_id is_it_user_private_forum_id INT DEFAULT NULL, CHANGE hidden hidden TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE forum_forum CHANGE category_id category_id INT DEFAULT NULL, CHANGE posts posts INT DEFAULT NULL, CHANGE topics topics INT DEFAULT NULL, CHANGE password password VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_post CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE soft_delete soft_delete TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE forum_post_conversation CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE soft_delete soft_delete TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_private_forum CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE password password VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE soft_delete soft_delete TINYINT(1) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
