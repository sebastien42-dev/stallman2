<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124120338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_planning (id INT AUTO_INCREMENT NOT NULL, salles_id INT DEFAULT NULL, classes_id INT DEFAULT NULL, matieres_id INT DEFAULT NULL, formateur_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, INDEX IDX_92755626B11E4946 (salles_id), INDEX IDX_927556269E225B24 (classes_id), INDEX IDX_9275562682350831 (matieres_id), INDEX IDX_92755626155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_planning ADD CONSTRAINT FK_92755626B11E4946 FOREIGN KEY (salles_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE event_planning ADD CONSTRAINT FK_927556269E225B24 FOREIGN KEY (classes_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE event_planning ADD CONSTRAINT FK_9275562682350831 FOREIGN KEY (matieres_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE event_planning ADD CONSTRAINT FK_92755626155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_planning');
    }
}
