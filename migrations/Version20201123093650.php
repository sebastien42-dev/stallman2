<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123093650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, libelle_classe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe_user (classe_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9380A3AF8F5EA509 (classe_id), INDEX IDX_9380A3AFA76ED395 (user_id), PRIMARY KEY(classe_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe_matiere (classe_id INT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_EB8D372B8F5EA509 (classe_id), INDEX IDX_EB8D372BF46CD258 (matiere_id), PRIMARY KEY(classe_id, matiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classe_user ADD CONSTRAINT FK_9380A3AF8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe_user ADD CONSTRAINT FK_9380A3AFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe_matiere ADD CONSTRAINT FK_EB8D372B8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe_matiere ADD CONSTRAINT FK_EB8D372BF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe_user DROP FOREIGN KEY FK_9380A3AF8F5EA509');
        $this->addSql('ALTER TABLE classe_matiere DROP FOREIGN KEY FK_EB8D372B8F5EA509');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE classe_user');
        $this->addSql('DROP TABLE classe_matiere');
    }
}
