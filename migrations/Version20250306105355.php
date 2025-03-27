<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306105355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, telephone VARCHAR(14) NOT NULL, email VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tournois_participant (tournois_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_E3C40EF4752534C (tournois_id), INDEX IDX_E3C40EF49D1C3019 (participant_id), PRIMARY KEY(tournois_id, participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tournois_participant ADD CONSTRAINT FK_E3C40EF4752534C FOREIGN KEY (tournois_id) REFERENCES tournois (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournois_participant ADD CONSTRAINT FK_E3C40EF49D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournois ADD CONSTRAINT FK_D7AAF97BCF5E72D FOREIGN KEY (categorie_id) REFERENCES cat_tournois (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournois_participant DROP FOREIGN KEY FK_E3C40EF4752534C');
        $this->addSql('ALTER TABLE tournois_participant DROP FOREIGN KEY FK_E3C40EF49D1C3019');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE tournois_participant');
        $this->addSql('ALTER TABLE tournois DROP FOREIGN KEY FK_D7AAF97BCF5E72D');
    }
}
