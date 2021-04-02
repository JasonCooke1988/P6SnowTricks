<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319143500 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick_video DROP FOREIGN KEY FK_B7E8DA93B46B9EE8');
        $this->addSql('DROP INDEX IDX_B7E8DA93B46B9EE8 ON trick_video');
        $this->addSql('ALTER TABLE trick_video CHANGE trick_id_id trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick_video ADD CONSTRAINT FK_B7E8DA93B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_B7E8DA93B281BE2E ON trick_video (trick_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick_video DROP FOREIGN KEY FK_B7E8DA93B281BE2E');
        $this->addSql('DROP INDEX IDX_B7E8DA93B281BE2E ON trick_video');
        $this->addSql('ALTER TABLE trick_video CHANGE trick_id trick_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick_video ADD CONSTRAINT FK_B7E8DA93B46B9EE8 FOREIGN KEY (trick_id_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_B7E8DA93B46B9EE8 ON trick_video (trick_id_id)');
    }
}
