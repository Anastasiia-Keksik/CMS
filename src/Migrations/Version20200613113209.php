<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200613113209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(120) DEFAULT NULL, last_name VARCHAR(120) DEFAULT NULL, email VARCHAR(160) NOT NULL, paypal VARCHAR(160) DEFAULT NULL, twitter VARCHAR(15) DEFAULT NULL, facebook VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, last_online DATETIME DEFAULT NULL, country VARCHAR(64) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, linkedin VARCHAR(64) DEFAULT NULL, reddit VARCHAR(64) DEFAULT NULL, skype VARCHAR(255) DEFAULT NULL, flickr VARCHAR(64) DEFAULT NULL, instagram VARCHAR(64) DEFAULT NULL, youtube VARCHAR(32) DEFAULT NULL, newsletter TINYINT(1) DEFAULT NULL, template VARCHAR(32) DEFAULT NULL, agreed_terms_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_7D3656A4F85E0677 (username), UNIQUE INDEX UNIQ_7D3656A4E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_category (id INT AUTO_INCREMENT NOT NULL, is_it_user_private_forum_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, order_value INT NOT NULL, hidden TINYINT(1) DEFAULT NULL, INDEX IDX_21BF94263A1E2C91 (is_it_user_private_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_forum (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, posts INT DEFAULT NULL, topics INT DEFAULT NULL, password VARCHAR(32) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, icon LONGBLOB DEFAULT NULL, INDEX IDX_9D5F181A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, forum_topic_id INT NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, likes INT NOT NULL, INDEX IDX_996BCC5AF675F31B (author_id), INDEX IDX_996BCC5A38A6ADDA (forum_topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post_conversation (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, post_id INT NOT NULL, content VARCHAR(255) NOT NULL, soft_delete TINYINT(1) NOT NULL, INDEX IDX_F8E532D9F675F31B (author_id), INDEX IDX_F8E532D94B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, forum_id INT NOT NULL, title VARCHAR(255) NOT NULL, soft_delete TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, last_post_at DATETIME NOT NULL, INDEX IDX_853478CCF675F31B (author_id), INDEX IDX_853478CC29CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_category (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_98B9EFCAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_child (id INT AUTO_INCREMENT NOT NULL, main_menu_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, url_value VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_8BE7032D91223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_category (id INT AUTO_INCREMENT NOT NULL, main_menu_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_A4D7D44091223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_child (id INT AUTO_INCREMENT NOT NULL, main_menu_sub_category_id INT NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, url_value VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_59BB1CEEEB380F38 (main_menu_sub_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passwords (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_ED822B16A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_category (id INT AUTO_INCREMENT NOT NULL, is_it_user_private_forum_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, order_value INT NOT NULL, hidden TINYINT(1) DEFAULT NULL, INDEX IDX_4A3A4B7F3A1E2C91 (is_it_user_private_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_forum (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, posts INT DEFAULT NULL, topics INT DEFAULT NULL, password VARCHAR(32) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, icon LONGBLOB DEFAULT NULL, INDEX IDX_3444D18F12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, forum_topic_id INT NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, likes INT NOT NULL, INDEX IDX_3E0C0AB6F675F31B (author_id), INDEX IDX_3E0C0AB638A6ADDA (forum_topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_post_conversation (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, post_id INT NOT NULL, content VARCHAR(255) NOT NULL, soft_delete TINYINT(1) NOT NULL, INDEX IDX_41349B88F675F31B (author_id), INDEX IDX_41349B884B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, forum_id INT NOT NULL, title VARCHAR(255) NOT NULL, soft_delete TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, last_post_at DATETIME NOT NULL, is_it_user_forum_topic TINYINT(1) DEFAULT NULL, INDEX IDX_2C2FB159F675F31B (author_id), INDEX IDX_2C2FB15929CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_private_forum (id INT AUTO_INCREMENT NOT NULL, user_admin_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, password VARCHAR(64) DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_47EC4F6284A66610 (user_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_private_forum_account (user_private_forum_id INT NOT NULL, account_id INT NOT NULL, INDEX IDX_A2A64A2FF34F27A0 (user_private_forum_id), INDEX IDX_A2A64A2F9B6B5FBA (account_id), PRIMARY KEY(user_private_forum_id, account_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_category ADD CONSTRAINT FK_21BF94263A1E2C91 FOREIGN KEY (is_it_user_private_forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE forum_forum ADD CONSTRAINT FK_9D5F181A12469DE2 FOREIGN KEY (category_id) REFERENCES forum_category (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AF675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5A38A6ADDA FOREIGN KEY (forum_topic_id) REFERENCES forum_topic (id)');
        $this->addSql('ALTER TABLE forum_post_conversation ADD CONSTRAINT FK_F8E532D9F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_post_conversation ADD CONSTRAINT FK_F8E532D94B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCF675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CC29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum_forum (id)');
        $this->addSql('ALTER TABLE main_menu_category ADD CONSTRAINT FK_98B9EFCAA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE main_menu_child ADD CONSTRAINT FK_8BE7032D91223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_category ADD CONSTRAINT FK_A4D7D44091223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_child ADD CONSTRAINT FK_59BB1CEEEB380F38 FOREIGN KEY (main_menu_sub_category_id) REFERENCES main_menu_sub_category (id)');
        $this->addSql('ALTER TABLE passwords ADD CONSTRAINT FK_ED822B16A76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_category ADD CONSTRAINT FK_4A3A4B7F3A1E2C91 FOREIGN KEY (is_it_user_private_forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE user_forum_forum ADD CONSTRAINT FK_3444D18F12469DE2 FOREIGN KEY (category_id) REFERENCES user_forum_category (id)');
        $this->addSql('ALTER TABLE user_forum_post ADD CONSTRAINT FK_3E0C0AB6F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_post ADD CONSTRAINT FK_3E0C0AB638A6ADDA FOREIGN KEY (forum_topic_id) REFERENCES user_forum_topic (id)');
        $this->addSql('ALTER TABLE user_forum_post_conversation ADD CONSTRAINT FK_41349B88F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_post_conversation ADD CONSTRAINT FK_41349B884B89032C FOREIGN KEY (post_id) REFERENCES user_forum_post (id)');
        $this->addSql('ALTER TABLE user_forum_topic ADD CONSTRAINT FK_2C2FB159F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_topic ADD CONSTRAINT FK_2C2FB15929CCBAD0 FOREIGN KEY (forum_id) REFERENCES user_forum_forum (id)');
        $this->addSql('ALTER TABLE user_private_forum ADD CONSTRAINT FK_47EC4F6284A66610 FOREIGN KEY (user_admin_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_private_forum_account ADD CONSTRAINT FK_A2A64A2FF34F27A0 FOREIGN KEY (user_private_forum_id) REFERENCES user_private_forum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_private_forum_account ADD CONSTRAINT FK_A2A64A2F9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AF675F31B');
        $this->addSql('ALTER TABLE forum_post_conversation DROP FOREIGN KEY FK_F8E532D9F675F31B');
        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CCF675F31B');
        $this->addSql('ALTER TABLE main_menu_category DROP FOREIGN KEY FK_98B9EFCAA76ED395');
        $this->addSql('ALTER TABLE passwords DROP FOREIGN KEY FK_ED822B16A76ED395');
        $this->addSql('ALTER TABLE user_forum_post DROP FOREIGN KEY FK_3E0C0AB6F675F31B');
        $this->addSql('ALTER TABLE user_forum_post_conversation DROP FOREIGN KEY FK_41349B88F675F31B');
        $this->addSql('ALTER TABLE user_forum_topic DROP FOREIGN KEY FK_2C2FB159F675F31B');
        $this->addSql('ALTER TABLE user_private_forum DROP FOREIGN KEY FK_47EC4F6284A66610');
        $this->addSql('ALTER TABLE user_private_forum_account DROP FOREIGN KEY FK_A2A64A2F9B6B5FBA');
        $this->addSql('ALTER TABLE forum_forum DROP FOREIGN KEY FK_9D5F181A12469DE2');
        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CC29CCBAD0');
        $this->addSql('ALTER TABLE forum_post_conversation DROP FOREIGN KEY FK_F8E532D94B89032C');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5A38A6ADDA');
        $this->addSql('ALTER TABLE main_menu_child DROP FOREIGN KEY FK_8BE7032D91223235');
        $this->addSql('ALTER TABLE main_menu_sub_category DROP FOREIGN KEY FK_A4D7D44091223235');
        $this->addSql('ALTER TABLE main_menu_sub_child DROP FOREIGN KEY FK_59BB1CEEEB380F38');
        $this->addSql('ALTER TABLE user_forum_forum DROP FOREIGN KEY FK_3444D18F12469DE2');
        $this->addSql('ALTER TABLE user_forum_topic DROP FOREIGN KEY FK_2C2FB15929CCBAD0');
        $this->addSql('ALTER TABLE user_forum_post_conversation DROP FOREIGN KEY FK_41349B884B89032C');
        $this->addSql('ALTER TABLE user_forum_post DROP FOREIGN KEY FK_3E0C0AB638A6ADDA');
        $this->addSql('ALTER TABLE forum_category DROP FOREIGN KEY FK_21BF94263A1E2C91');
        $this->addSql('ALTER TABLE user_forum_category DROP FOREIGN KEY FK_4A3A4B7F3A1E2C91');
        $this->addSql('ALTER TABLE user_private_forum_account DROP FOREIGN KEY FK_A2A64A2FF34F27A0');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_forum');
        $this->addSql('DROP TABLE forum_post');
        $this->addSql('DROP TABLE forum_post_conversation');
        $this->addSql('DROP TABLE forum_topic');
        $this->addSql('DROP TABLE main_menu_category');
        $this->addSql('DROP TABLE main_menu_child');
        $this->addSql('DROP TABLE main_menu_sub_category');
        $this->addSql('DROP TABLE main_menu_sub_child');
        $this->addSql('DROP TABLE passwords');
        $this->addSql('DROP TABLE user_forum_category');
        $this->addSql('DROP TABLE user_forum_forum');
        $this->addSql('DROP TABLE user_forum_post');
        $this->addSql('DROP TABLE user_forum_post_conversation');
        $this->addSql('DROP TABLE user_forum_topic');
        $this->addSql('DROP TABLE user_private_forum');
        $this->addSql('DROP TABLE user_private_forum_account');
    }
}
