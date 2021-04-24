<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423144645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX id ON annonces');
        $this->addSql('DROP INDEX id_2 ON annonces');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F894C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX id ON annonces (id)');
        $this->addSql('CREATE INDEX id_2 ON annonces (id)');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F894C2885D7');
    }
}
