<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423082011 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX id ON credits');
        $this->addSql('DROP INDEX id ON like_annonces');
        $this->addSql('DROP INDEX id_2 ON like_annonces');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX id ON credits (id)');
        $this->addSql('CREATE INDEX id ON like_annonces (id)');
        $this->addSql('CREATE UNIQUE INDEX id_2 ON like_annonces (id)');
    }
}
