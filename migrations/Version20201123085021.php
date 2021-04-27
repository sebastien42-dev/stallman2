<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123085021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE civilite (id INT AUTO_INCREMENT NOT NULL, libelle_civilite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        //$this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64957889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
        //$this->addSql('CREATE INDEX IDX_8D93D64957889920 ON user (fonction_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE civilite');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64957889920');
        $this->addSql('DROP INDEX IDX_8D93D64957889920 ON user');
    }
}
