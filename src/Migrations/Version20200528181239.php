<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200528181239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum_category (id INT AUTO_INCREMENT NOT NULL, is_it_user_private_forum_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, order_value INT NOT NULL, hidden TINYINT(1) DEFAULT NULL, INDEX IDX_21BF94263A1E2C91 (is_it_user_private_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_forum (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, posts INT DEFAULT NULL, topics INT DEFAULT NULL, password VARCHAR(32) DEFAULT NULL, INDEX IDX_9D5F181A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, likes INT NOT NULL, INDEX IDX_996BCC5AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post_conversation (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, post_id INT NOT NULL, content VARCHAR(255) NOT NULL, solf_delete TINYINT(1) NOT NULL, INDEX IDX_F8E532D9F675F31B (author_id), INDEX IDX_F8E532D94B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, forum_id INT NOT NULL, title VARCHAR(255) NOT NULL, soft_delete TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, last_post_at DATETIME NOT NULL, INDEX IDX_853478CCF675F31B (author_id), INDEX IDX_853478CC29CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_private_forum (id INT AUTO_INCREMENT NOT NULL, user_admin_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, password VARCHAR(64) DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_47EC4F6284A66610 (user_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_private_forum_account (user_private_forum_id INT NOT NULL, account_id INT NOT NULL, INDEX IDX_A2A64A2FF34F27A0 (user_private_forum_id), INDEX IDX_A2A64A2F9B6B5FBA (account_id), PRIMARY KEY(user_private_forum_id, account_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_category ADD CONSTRAINT FK_21BF94263A1E2C91 FOREIGN KEY (is_it_user_private_forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE forum_forum ADD CONSTRAINT FK_9D5F181A12469DE2 FOREIGN KEY (category_id) REFERENCES forum_category (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AF675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_post_conversation ADD CONSTRAINT FK_F8E532D9F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_post_conversation ADD CONSTRAINT FK_F8E532D94B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCF675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CC29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum_forum (id)');
        $this->addSql('ALTER TABLE user_private_forum ADD CONSTRAINT FK_47EC4F6284A66610 FOREIGN KEY (user_admin_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_private_forum_account ADD CONSTRAINT FK_A2A64A2FF34F27A0 FOREIGN KEY (user_private_forum_id) REFERENCES user_private_forum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_private_forum_account ADD CONSTRAINT FK_A2A64A2F9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE account CHANGE roles roles JSON NOT NULL, CHANGE first_name first_name VARCHAR(120) DEFAULT NULL, CHANGE last_name last_name VARCHAR(120) DEFAULT NULL, CHANGE paypal paypal VARCHAR(160) DEFAULT NULL, CHANGE twitter twitter VARCHAR(15) DEFAULT NULL, CHANGE facebook facebook VARCHAR(50) DEFAULT NULL, CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE last_online last_online DATETIME DEFAULT NULL, CHANGE country country VARCHAR(64) DEFAULT NULL, CHANGE city city VARCHAR(64) DEFAULT NULL, CHANGE linkedin linkedin VARCHAR(64) DEFAULT NULL, CHANGE reddit reddit VARCHAR(64) DEFAULT NULL, CHANGE skype skype VARCHAR(255) DEFAULT NULL, CHANGE flickr flickr VARCHAR(64) DEFAULT NULL, CHANGE instagram instagram VARCHAR(64) DEFAULT NULL, CHANGE youtube youtube VARCHAR(32) DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL, CHANGE template template VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_forum DROP FOREIGN KEY FK_9D5F181A12469DE2');
        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CC29CCBAD0');
        $this->addSql('ALTER TABLE forum_post_conversation DROP FOREIGN KEY FK_F8E532D94B89032C');
        $this->addSql('ALTER TABLE forum_category DROP FOREIGN KEY FK_21BF94263A1E2C91');
        $this->addSql('ALTER TABLE user_private_forum_account DROP FOREIGN KEY FK_A2A64A2FF34F27A0');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_forum');
        $this->addSql('DROP TABLE forum_post');
        $this->addSql('DROP TABLE forum_post_conversation');
        $this->addSql('DROP TABLE forum_topic');
        $this->addSql('DROP TABLE user_private_forum');
        $this->addSql('DROP TABLE user_private_forum_account');
        $this->addSql('ALTER TABLE account CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE first_name first_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE paypal paypal VARCHAR(160) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE twitter twitter VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE facebook facebook VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE modified_at modified_at DATETIME DEFAULT \'NULL\', CHANGE last_online last_online DATETIME DEFAULT \'NULL\', CHANGE country country VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE linkedin linkedin VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE reddit reddit VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE skype skype VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE flickr flickr VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE instagram instagram VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE youtube youtube VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\', CHANGE template template VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE main_menu_category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE main_menu_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE main_menu_sub_child CHANGE url_value url_value VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE passwords CHANGE user_id user_id INT DEFAULT NULL');
    }
}
