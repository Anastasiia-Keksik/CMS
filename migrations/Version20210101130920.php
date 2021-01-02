<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210101130920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forum_members (id VARCHAR(36) NOT NULL, forum_id VARCHAR(36) NOT NULL, member_id VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, role LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', pending TINYINT(1) NOT NULL, rank SMALLINT DEFAULT NULL, INDEX IDX_B46C9F8E29CCBAD0 (forum_id), INDEX IDX_B46C9F8E7597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id VARCHAR(36) NOT NULL, author_id VARCHAR(36) NOT NULL, forum_topic_id VARCHAR(36) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, likes INT NOT NULL, INDEX IDX_996BCC5AF675F31B (author_id), INDEX IDX_996BCC5A38A6ADDA (forum_topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post_conversation (id VARCHAR(36) NOT NULL, author_id VARCHAR(36) DEFAULT NULL, post_id VARCHAR(36) NOT NULL, content VARCHAR(255) NOT NULL, soft_delete TINYINT(1) NOT NULL, INDEX IDX_F8E532D9F675F31B (author_id), INDEX IDX_F8E532D94B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic (id VARCHAR(36) NOT NULL, author_id VARCHAR(36) NOT NULL, forum_id VARCHAR(36) NOT NULL, title VARCHAR(255) NOT NULL, soft_delete TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, last_post_at DATETIME NOT NULL, INDEX IDX_853478CCF675F31B (author_id), INDEX IDX_853478CC29CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id VARCHAR(36) NOT NULL, account_id VARCHAR(36) NOT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, albums INT NOT NULL, photos INT NOT NULL, UNIQUE INDEX UNIQ_472B783A9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_album (id VARCHAR(36) NOT NULL, gallery_id VARCHAR(36) NOT NULL, cover_id VARCHAR(36) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, photos INT NOT NULL, INDEX IDX_DD05BE604E7AF8F (gallery_id), UNIQUE INDEX UNIQ_DD05BE60922726E9 (cover_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_photos (id VARCHAR(36) NOT NULL, album_id VARCHAR(36) DEFAULT NULL, gallery_id_id VARCHAR(36) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL, soft_delete TINYINT(1) NOT NULL, original_filename VARCHAR(255) NOT NULL, INDEX IDX_AAF50C7B1137ABCF (album_id), INDEX IDX_AAF50C7B9BD94E74 (gallery_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ip_last_logon_log (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, ip VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, logout_at DATETIME DEFAULT NULL, session_id VARCHAR(255) DEFAULT NULL, INDEX IDX_3F8B6CFBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lancuszek (id VARCHAR(36) NOT NULL, osoba_polecajaca_id VARCHAR(36) NOT NULL, osoba_polecona_id VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C6C2260E8CD2B617 (osoba_polecajaca_id), INDEX IDX_C6C2260EEF5FE62F (osoba_polecona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_category (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) DEFAULT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, hidden TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_98B9EFCAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_child (id VARCHAR(36) NOT NULL, main_menu_category_id VARCHAR(36) NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, url_value VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_8BE7032D91223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_category (id VARCHAR(36) NOT NULL, main_menu_category_id VARCHAR(36) NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_A4D7D44091223235 (main_menu_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE main_menu_sub_child (id VARCHAR(36) NOT NULL, main_menu_sub_category_id VARCHAR(36) NOT NULL, name VARCHAR(64) NOT NULL, order_number SMALLINT UNSIGNED NOT NULL, disabled TINYINT(1) NOT NULL, url_value VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, INDEX IDX_59BB1CEEEB380F38 (main_menu_sub_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE passwords (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_ED822B16A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_likes (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) NOT NULL, post_id VARCHAR(36) NOT NULL, INDEX IDX_FBCD156CA76ED395 (user_id), INDEX IDX_FBCD156C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile_design_settings (id VARCHAR(36) NOT NULL, account_id VARCHAR(36) NOT NULL, settings LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_53216CA99B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id VARCHAR(36) NOT NULL, user_id VARCHAR(36) DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_post (id VARCHAR(36) NOT NULL, account_id VARCHAR(36) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, soft_delete TINYINT(1) NOT NULL, likes INT NOT NULL, background_filename VARCHAR(255) DEFAULT NULL, INDEX IDX_159BBFE99B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_post_bgholder (id VARCHAR(36) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_post_comment (id VARCHAR(36) NOT NULL, author_id VARCHAR(36) NOT NULL, post_id VARCHAR(36) DEFAULT NULL, under_another_comment_id VARCHAR(36) DEFAULT NULL, content LONGTEXT NOT NULL, soft_delete TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, likes INT NOT NULL, INDEX IDX_753F4C60F675F31B (author_id), INDEX IDX_753F4C604B89032C (post_id), INDEX IDX_753F4C607B2390EB (under_another_comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE themes (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(36) NOT NULL, under_forum_id VARCHAR(36) DEFAULT NULL, under SMALLINT NOT NULL, modal_bg_type SMALLINT DEFAULT NULL, modal_bg VARCHAR(255) DEFAULT NULL, modal_bg_sound VARCHAR(255) DEFAULT NULL, INDEX IDX_154232DEA76ED395 (user_id), UNIQUE INDEX UNIQ_154232DE520CA1B7 (under_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE themese (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE under_forum (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_category (id VARCHAR(36) NOT NULL, is_it_user_private_forum_id VARCHAR(36) DEFAULT NULL, name VARCHAR(32) NOT NULL, order_value INT NOT NULL, hidden TINYINT(1) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_4A3A4B7F3A1E2C91 (is_it_user_private_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_forum (id VARCHAR(36) NOT NULL, category_id VARCHAR(36) DEFAULT NULL, name VARCHAR(180) NOT NULL, posts INT DEFAULT NULL, topics INT DEFAULT NULL, password VARCHAR(32) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, icon LONGBLOB DEFAULT NULL, order_value INT NOT NULL, INDEX IDX_3444D18F12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_post (id VARCHAR(36) NOT NULL, author_id VARCHAR(36) NOT NULL, forum_topic_id VARCHAR(36) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, soft_delete TINYINT(1) NOT NULL, likes INT NOT NULL, INDEX IDX_3E0C0AB6F675F31B (author_id), INDEX IDX_3E0C0AB638A6ADDA (forum_topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_post_conversation (id VARCHAR(36) NOT NULL, author_id VARCHAR(36) DEFAULT NULL, post_id VARCHAR(36) NOT NULL, content VARCHAR(255) NOT NULL, soft_delete TINYINT(1) NOT NULL, INDEX IDX_41349B88F675F31B (author_id), INDEX IDX_41349B884B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_ranks (id INT AUTO_INCREMENT NOT NULL, forum_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, start INT DEFAULT NULL, finish INT DEFAULT NULL, special TINYINT(1) DEFAULT NULL, image_long VARCHAR(255) DEFAULT NULL, image_wide VARCHAR(255) DEFAULT NULL, INDEX IDX_7A89CF5629CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_ranks_account (user_forum_ranks_id INT NOT NULL, account_id VARCHAR(36) NOT NULL, INDEX IDX_2ABF1F84D676674D (user_forum_ranks_id), INDEX IDX_2ABF1F849B6B5FBA (account_id), PRIMARY KEY(user_forum_ranks_id, account_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_forum_topic (id VARCHAR(36) NOT NULL, author_id VARCHAR(36) NOT NULL, forum_id VARCHAR(36) NOT NULL, title VARCHAR(255) NOT NULL, soft_delete TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, last_post_at DATETIME DEFAULT NULL, sticky TINYINT(1) NOT NULL, replies INT NOT NULL, views INT NOT NULL, INDEX IDX_2C2FB159F675F31B (author_id), INDEX IDX_2C2FB15929CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_private_forum (id VARCHAR(36) NOT NULL, user_admin_id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, password VARCHAR(64) DEFAULT NULL, soft_delete TINYINT(1) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_47EC4F6284A66610 (user_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(36) NOT NULL, money INT UNSIGNED DEFAULT 0 NOT NULL, event_money INT UNSIGNED DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_7C68921FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_members ADD CONSTRAINT FK_B46C9F8E29CCBAD0 FOREIGN KEY (forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE forum_members ADD CONSTRAINT FK_B46C9F8E7597D3FE FOREIGN KEY (member_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AF675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5A38A6ADDA FOREIGN KEY (forum_topic_id) REFERENCES forum_topic (id)');
        $this->addSql('ALTER TABLE forum_post_conversation ADD CONSTRAINT FK_F8E532D9F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_post_conversation ADD CONSTRAINT FK_F8E532D94B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCF675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CC29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum_forum (id)');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE gallery_album ADD CONSTRAINT FK_DD05BE604E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE gallery_album ADD CONSTRAINT FK_DD05BE60922726E9 FOREIGN KEY (cover_id) REFERENCES gallery_photos (id)');
        $this->addSql('ALTER TABLE gallery_photos ADD CONSTRAINT FK_AAF50C7B1137ABCF FOREIGN KEY (album_id) REFERENCES gallery_album (id)');
        $this->addSql('ALTER TABLE gallery_photos ADD CONSTRAINT FK_AAF50C7B9BD94E74 FOREIGN KEY (gallery_id_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE ip_last_logon_log ADD CONSTRAINT FK_3F8B6CFBA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE lancuszek ADD CONSTRAINT FK_C6C2260E8CD2B617 FOREIGN KEY (osoba_polecajaca_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE lancuszek ADD CONSTRAINT FK_C6C2260EEF5FE62F FOREIGN KEY (osoba_polecona_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE main_menu_category ADD CONSTRAINT FK_98B9EFCAA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE main_menu_child ADD CONSTRAINT FK_8BE7032D91223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_category ADD CONSTRAINT FK_A4D7D44091223235 FOREIGN KEY (main_menu_category_id) REFERENCES main_menu_category (id)');
        $this->addSql('ALTER TABLE main_menu_sub_child ADD CONSTRAINT FK_59BB1CEEEB380F38 FOREIGN KEY (main_menu_sub_category_id) REFERENCES main_menu_sub_category (id)');
        $this->addSql('ALTER TABLE passwords ADD CONSTRAINT FK_ED822B16A76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE posts_likes ADD CONSTRAINT FK_FBCD156CA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE posts_likes ADD CONSTRAINT FK_FBCD156C4B89032C FOREIGN KEY (post_id) REFERENCES user_forum_post (id)');
        $this->addSql('ALTER TABLE profile_design_settings ADD CONSTRAINT FK_53216CA99B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE social_post ADD CONSTRAINT FK_159BBFE99B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE social_post_comment ADD CONSTRAINT FK_753F4C60F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE social_post_comment ADD CONSTRAINT FK_753F4C604B89032C FOREIGN KEY (post_id) REFERENCES social_post (id)');
        $this->addSql('ALTER TABLE social_post_comment ADD CONSTRAINT FK_753F4C607B2390EB FOREIGN KEY (under_another_comment_id) REFERENCES social_post_comment (id)');
        $this->addSql('ALTER TABLE themes ADD CONSTRAINT FK_154232DEA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE themes ADD CONSTRAINT FK_154232DE520CA1B7 FOREIGN KEY (under_forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE user_forum_category ADD CONSTRAINT FK_4A3A4B7F3A1E2C91 FOREIGN KEY (is_it_user_private_forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE user_forum_forum ADD CONSTRAINT FK_3444D18F12469DE2 FOREIGN KEY (category_id) REFERENCES user_forum_category (id)');
        $this->addSql('ALTER TABLE user_forum_post ADD CONSTRAINT FK_3E0C0AB6F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_post ADD CONSTRAINT FK_3E0C0AB638A6ADDA FOREIGN KEY (forum_topic_id) REFERENCES user_forum_topic (id)');
        $this->addSql('ALTER TABLE user_forum_post_conversation ADD CONSTRAINT FK_41349B88F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_post_conversation ADD CONSTRAINT FK_41349B884B89032C FOREIGN KEY (post_id) REFERENCES user_forum_post (id)');
        $this->addSql('ALTER TABLE user_forum_ranks ADD CONSTRAINT FK_7A89CF5629CCBAD0 FOREIGN KEY (forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE user_forum_ranks_account ADD CONSTRAINT FK_2ABF1F84D676674D FOREIGN KEY (user_forum_ranks_id) REFERENCES user_forum_ranks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_forum_ranks_account ADD CONSTRAINT FK_2ABF1F849B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_forum_topic ADD CONSTRAINT FK_2C2FB159F675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE user_forum_topic ADD CONSTRAINT FK_2C2FB15929CCBAD0 FOREIGN KEY (forum_id) REFERENCES user_forum_forum (id)');
        $this->addSql('ALTER TABLE user_private_forum ADD CONSTRAINT FK_47EC4F6284A66610 FOREIGN KEY (user_admin_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921FA76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE account_signature ADD CONSTRAINT FK_8CF2D91A9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE chat_conversation ADD CONSTRAINT FK_74654F68BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES chat_message (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC169AC0396 FOREIGN KEY (conversation_id) REFERENCES chat_conversation (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16A76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE chat_participant ADD CONSTRAINT FK_E8ED9C89A76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE chat_participant ADD CONSTRAINT FK_E8ED9C899AC0396 FOREIGN KEY (conversation_id) REFERENCES chat_conversation (id)');
        $this->addSql('ALTER TABLE comic ADD CONSTRAINT FK_5B7EA5AA987AE85A FOREIGN KEY (category1_id) REFERENCES comic_categories (id)');
        $this->addSql('ALTER TABLE comic ADD CONSTRAINT FK_5B7EA5AA8ACF47B4 FOREIGN KEY (category2_id) REFERENCES comic_categories (id)');
        $this->addSql('ALTER TABLE comic ADD CONSTRAINT FK_5B7EA5AA327320D1 FOREIGN KEY (category3_id) REFERENCES comic_categories (id)');
        $this->addSql('ALTER TABLE comic ADD CONSTRAINT FK_5B7EA5AAF675F31B FOREIGN KEY (author_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE comic_episode ADD CONSTRAINT FK_F9176841D663094A FOREIGN KEY (comic_id) REFERENCES comic (id)');
        $this->addSql('ALTER TABLE comic_subscriptions ADD CONSTRAINT FK_AFD852F51E35465B FOREIGN KEY (comic_id_id) REFERENCES comic (id)');
        $this->addSql('ALTER TABLE comic_subscriptions ADD CONSTRAINT FK_AFD852F59D86650F FOREIGN KEY (user_id_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638B746C43A FOREIGN KEY (account_source_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638BF2E05E7 FOREIGN KEY (account_targets_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C501A76ED395 FOREIGN KEY (user_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C501C4F2D6B1 FOREIGN KEY (social_post_id) REFERENCES social_post (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C501C43C2351 FOREIGN KEY (social_post_comment_id) REFERENCES social_post_comment (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C501F5ECAF96 FOREIGN KEY (posts_likes_id) REFERENCES posts_likes (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C50138A6ADDA FOREIGN KEY (forum_topic_id) REFERENCES user_forum_topic (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C501F211D522 FOREIGN KEY (forum_posts_id) REFERENCES user_forum_post (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C5015F870BB4 FOREIGN KEY (gallery_photos_id) REFERENCES gallery_photos (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C5016540A7A2 FOREIGN KEY (comic_episodes_id) REFERENCES comic_episode (id)');
        $this->addSql('ALTER TABLE forum_category ADD CONSTRAINT FK_21BF94263A1E2C91 FOREIGN KEY (is_it_user_private_forum_id) REFERENCES user_private_forum (id)');
        $this->addSql('ALTER TABLE forum_forum ADD CONSTRAINT FK_9D5F181A12469DE2 FOREIGN KEY (category_id) REFERENCES forum_category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_post_conversation DROP FOREIGN KEY FK_F8E532D94B89032C');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5A38A6ADDA');
        $this->addSql('ALTER TABLE gallery_album DROP FOREIGN KEY FK_DD05BE604E7AF8F');
        $this->addSql('ALTER TABLE gallery_photos DROP FOREIGN KEY FK_AAF50C7B9BD94E74');
        $this->addSql('ALTER TABLE gallery_photos DROP FOREIGN KEY FK_AAF50C7B1137ABCF');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C5015F870BB4');
        $this->addSql('ALTER TABLE gallery_album DROP FOREIGN KEY FK_DD05BE60922726E9');
        $this->addSql('ALTER TABLE main_menu_child DROP FOREIGN KEY FK_8BE7032D91223235');
        $this->addSql('ALTER TABLE main_menu_sub_category DROP FOREIGN KEY FK_A4D7D44091223235');
        $this->addSql('ALTER TABLE main_menu_sub_child DROP FOREIGN KEY FK_59BB1CEEEB380F38');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C501F5ECAF96');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C501C4F2D6B1');
        $this->addSql('ALTER TABLE social_post_comment DROP FOREIGN KEY FK_753F4C604B89032C');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C501C43C2351');
        $this->addSql('ALTER TABLE social_post_comment DROP FOREIGN KEY FK_753F4C607B2390EB');
        $this->addSql('ALTER TABLE user_forum_forum DROP FOREIGN KEY FK_3444D18F12469DE2');
        $this->addSql('ALTER TABLE user_forum_topic DROP FOREIGN KEY FK_2C2FB15929CCBAD0');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C501F211D522');
        $this->addSql('ALTER TABLE posts_likes DROP FOREIGN KEY FK_FBCD156C4B89032C');
        $this->addSql('ALTER TABLE user_forum_post_conversation DROP FOREIGN KEY FK_41349B884B89032C');
        $this->addSql('ALTER TABLE user_forum_ranks_account DROP FOREIGN KEY FK_2ABF1F84D676674D');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C50138A6ADDA');
        $this->addSql('ALTER TABLE user_forum_post DROP FOREIGN KEY FK_3E0C0AB638A6ADDA');
        $this->addSql('ALTER TABLE forum_category DROP FOREIGN KEY FK_21BF94263A1E2C91');
        $this->addSql('ALTER TABLE forum_members DROP FOREIGN KEY FK_B46C9F8E29CCBAD0');
        $this->addSql('ALTER TABLE themes DROP FOREIGN KEY FK_154232DE520CA1B7');
        $this->addSql('ALTER TABLE user_forum_category DROP FOREIGN KEY FK_4A3A4B7F3A1E2C91');
        $this->addSql('ALTER TABLE user_forum_ranks DROP FOREIGN KEY FK_7A89CF5629CCBAD0');
        $this->addSql('DROP TABLE forum_members');
        $this->addSql('DROP TABLE forum_post');
        $this->addSql('DROP TABLE forum_post_conversation');
        $this->addSql('DROP TABLE forum_topic');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_album');
        $this->addSql('DROP TABLE gallery_photos');
        $this->addSql('DROP TABLE ip_last_logon_log');
        $this->addSql('DROP TABLE lancuszek');
        $this->addSql('DROP TABLE main_menu_category');
        $this->addSql('DROP TABLE main_menu_child');
        $this->addSql('DROP TABLE main_menu_sub_category');
        $this->addSql('DROP TABLE main_menu_sub_child');
        $this->addSql('DROP TABLE passwords');
        $this->addSql('DROP TABLE posts_likes');
        $this->addSql('DROP TABLE profile_design_settings');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE social_post');
        $this->addSql('DROP TABLE social_post_bgholder');
        $this->addSql('DROP TABLE social_post_comment');
        $this->addSql('DROP TABLE themes');
        $this->addSql('DROP TABLE themese');
        $this->addSql('DROP TABLE under_forum');
        $this->addSql('DROP TABLE user_forum_category');
        $this->addSql('DROP TABLE user_forum_forum');
        $this->addSql('DROP TABLE user_forum_post');
        $this->addSql('DROP TABLE user_forum_post_conversation');
        $this->addSql('DROP TABLE user_forum_ranks');
        $this->addSql('DROP TABLE user_forum_ranks_account');
        $this->addSql('DROP TABLE user_forum_topic');
        $this->addSql('DROP TABLE user_private_forum');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('ALTER TABLE account_signature DROP FOREIGN KEY FK_8CF2D91A9B6B5FBA');
        $this->addSql('ALTER TABLE chat_conversation DROP FOREIGN KEY FK_74654F68BA0E79C3');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC169AC0396');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC16A76ED395');
        $this->addSql('ALTER TABLE chat_participant DROP FOREIGN KEY FK_E8ED9C89A76ED395');
        $this->addSql('ALTER TABLE chat_participant DROP FOREIGN KEY FK_E8ED9C899AC0396');
        $this->addSql('ALTER TABLE comic DROP FOREIGN KEY FK_5B7EA5AA987AE85A');
        $this->addSql('ALTER TABLE comic DROP FOREIGN KEY FK_5B7EA5AA8ACF47B4');
        $this->addSql('ALTER TABLE comic DROP FOREIGN KEY FK_5B7EA5AA327320D1');
        $this->addSql('ALTER TABLE comic DROP FOREIGN KEY FK_5B7EA5AAF675F31B');
        $this->addSql('ALTER TABLE comic_episode DROP FOREIGN KEY FK_F9176841D663094A');
        $this->addSql('ALTER TABLE comic_subscriptions DROP FOREIGN KEY FK_AFD852F51E35465B');
        $this->addSql('ALTER TABLE comic_subscriptions DROP FOREIGN KEY FK_AFD852F59D86650F');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638B746C43A');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638BF2E05E7');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C501A76ED395');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C5016540A7A2');
        $this->addSql('ALTER TABLE forum_forum DROP FOREIGN KEY FK_9D5F181A12469DE2');
    }
}
