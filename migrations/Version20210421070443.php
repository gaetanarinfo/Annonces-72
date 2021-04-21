<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210421070443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE like_annonces (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, annonces_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX id ON vote_annonces');
        $this->addSql('DROP INDEX id_2 ON vote_annonces');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE like_annonces');
        $this->addSql('CREATE UNIQUE INDEX id ON vote_annonces (id)');
        $this->addSql('CREATE INDEX id_2 ON vote_annonces (id)');
    }
}
