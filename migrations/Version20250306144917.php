<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306144917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, telephone VARCHAR(14) NOT NULL, email VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(40) NOT NULL, date DATETIME NOT NULL, date_creation DATETIME NOT NULL, nb_participants INT DEFAULT NULL, categorie_id INT DEFAULT NULL, INDEX IDX_18AFD9DFBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tournoi_participant (tournoi_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_9C531479F607770A (tournoi_id), INDEX IDX_9C5314799D1C3019 (participant_id), PRIMARY KEY(tournoi_id, participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DFBCF5E72D FOREIGN KEY (categorie_id) REFERENCES cat_tournois (id)');
        $this->addSql('ALTER TABLE tournoi_participant ADD CONSTRAINT FK_9C531479F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournoi_participant ADD CONSTRAINT FK_9C5314799D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE participants');
        $this->addSql('DROP TABLE tournois');
        $this->addSql('DROP TABLE tournois_participant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participants (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(14) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tournois (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATETIME NOT NULL, date_creation DATETIME NOT NULL, categorie_id INT DEFAULT NULL, INDEX IDX_D7AAF97BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tournois_participant (tournois_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_E3C40EF4752534C (tournois_id), INDEX IDX_E3C40EF49D1C3019 (participant_id), PRIMARY KEY(tournois_id, participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tournoi DROP FOREIGN KEY FK_18AFD9DFBCF5E72D');
        $this->addSql('ALTER TABLE tournoi_participant DROP FOREIGN KEY FK_9C531479F607770A');
        $this->addSql('ALTER TABLE tournoi_participant DROP FOREIGN KEY FK_9C5314799D1C3019');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE tournoi');
        $this->addSql('DROP TABLE tournoi_participant');
    }
}
