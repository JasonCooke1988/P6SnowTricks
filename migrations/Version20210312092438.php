<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312092438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E2F68B530');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9D86650F');
        $this->addSql('DROP INDEX IDX_D8F0A91E2F68B530 ON trick');
        $this->addSql('DROP INDEX IDX_D8F0A91E9D86650F ON trick');
        $this->addSql('ALTER TABLE trick ADD group_id_id INT NOT NULL, ADD user_id_id INT NOT NULL, DROP group_id, DROP user_id');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E2F68B530 FOREIGN KEY (group_id_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E2F68B530 ON trick (group_id_id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E9D86650F ON trick (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E2F68B530');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9D86650F');
        $this->addSql('DROP INDEX IDX_D8F0A91E2F68B530 ON trick');
        $this->addSql('DROP INDEX IDX_D8F0A91E9D86650F ON trick');
        $this->addSql('ALTER TABLE trick ADD group_id INT NOT NULL, ADD user_id INT NOT NULL, DROP group_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E2F68B530 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E2F68B530 ON trick (group_id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E9D86650F ON trick (user_id)');
    }
}
