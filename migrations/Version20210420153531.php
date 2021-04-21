<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210420153531 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_vote_annonces (user_id INT NOT NULL, vote_annonces_id INT NOT NULL, INDEX IDX_DB498816A76ED395 (user_id), INDEX IDX_DB498816FFA5B383 (vote_annonces_id), PRIMARY KEY(user_id, vote_annonces_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_vote_annonces ADD CONSTRAINT FK_DB498816A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_vote_annonces ADD CONSTRAINT FK_DB498816FFA5B383 FOREIGN KEY (vote_annonces_id) REFERENCES vote_annonces (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX id ON user');
        $this->addSql('DROP INDEX id ON vote_annonces');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_vote_annonces');
        $this->addSql('CREATE UNIQUE INDEX id ON user (id)');
        $this->addSql('CREATE UNIQUE INDEX id ON vote_annonces (id)');
    }
}
