<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429061731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces ADD visitor INT DEFAULT NULL');
        $this->addSql('DROP INDEX id ON mailbox');
        $this->addSql('DROP INDEX id_2 ON mailbox');
        $this->addSql('DROP INDEX id_3 ON mailbox');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP visitor');
        $this->addSql('CREATE INDEX id ON mailbox (id)');
        $this->addSql('CREATE UNIQUE INDEX id_2 ON mailbox (id)');
        $this->addSql('CREATE INDEX id_3 ON mailbox (id)');
    }
}
