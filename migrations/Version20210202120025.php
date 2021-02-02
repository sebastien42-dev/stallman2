<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210202120025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bill_lign (id INT AUTO_INCREMENT NOT NULL, bill_id INT DEFAULT NULL, package_id INT DEFAULT NULL, out_package_id INT DEFAULT NULL, quantity INT DEFAULT NULL, global_lign_value INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_641BBE9D1A8C12F5 (bill_id), INDEX IDX_641BBE9DF44CABFF (package_id), INDEX IDX_641BBE9D77E5DD6F (out_package_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bill_lign ADD CONSTRAINT FK_641BBE9D1A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE bill_lign ADD CONSTRAINT FK_641BBE9DF44CABFF FOREIGN KEY (package_id) REFERENCES package (id)');
        $this->addSql('ALTER TABLE bill_lign ADD CONSTRAINT FK_641BBE9D77E5DD6F FOREIGN KEY (out_package_id) REFERENCES out_package (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bill_lign');
    }
}
