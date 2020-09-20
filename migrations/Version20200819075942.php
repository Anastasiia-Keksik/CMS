<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819075942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_user_forum_post (account_id INT NOT NULL, user_forum_post_id INT NOT NULL, INDEX IDX_DE8B146C9B6B5FBA (account_id), INDEX IDX_DE8B146C54CBE30D (user_forum_post_id), PRIMARY KEY(account_id, user_forum_post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts_likes (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account_user_forum_post ADD CONSTRAINT FK_DE8B146C9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE account_user_forum_post ADD CONSTRAINT FK_DE8B146C54CBE30D FOREIGN KEY (user_forum_post_id) REFERENCES user_forum_post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account_user_forum_post');
        $this->addSql('DROP TABLE posts_likes');
    }
}
