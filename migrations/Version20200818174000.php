<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200818174000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_forum_post CHANGE soft_delete soft_delete TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user_forum_topic CHANGE modified_at modified_at DATETIME DEFAULT NULL, CHANGE last_post_at last_post_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_forum_post CHANGE soft_delete soft_delete TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_forum_topic CHANGE modified_at modified_at DATETIME NOT NULL, CHANGE last_post_at last_post_at DATETIME NOT NULL');
    }
}
