<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319041925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE url DROP FOREIGN KEY FK_F47645AE9D86650F');
        $this->addSql('DROP INDEX UNIQ_F47645AE9D86650F ON url');
        $this->addSql('ALTER TABLE url ADD user_id INT NOT NULL, DROP user_id_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE url ADD user_id_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE url ADD CONSTRAINT FK_F47645AE9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F47645AE9D86650F ON url (user_id_id)');
    }
}
