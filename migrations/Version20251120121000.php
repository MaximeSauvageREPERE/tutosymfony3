<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251120121000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Relation ManyToMany entre Produit et Tag';
    }

    public function up(Schema $schema): void
    {
        // Suppression de la table produit_tag si elle existe déjà
        $this->addSql('DROP TABLE IF EXISTS produit_tag');
        // Création de la table d'association produit_tag
        $this->addSql('CREATE TABLE produit_tag (produit_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_3C7A5A5CF347EFB (produit_id), INDEX IDX_3C7A5A5CBAD26311 (tag_id), PRIMARY KEY(produit_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_tag ADD CONSTRAINT FK_3C7A5A5CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_tag ADD CONSTRAINT FK_3C7A5A5CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        // Suppression de la contrainte FK si elle existe, puis suppression de la colonne produit_id
        try {
            $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783F347EFB');
        } catch (\Exception $e) {}
        $this->addSql('ALTER TABLE tag DROP COLUMN produit_id');
    }

    public function down(Schema $schema): void
    {
        // Ajout de la colonne produit_id sur tag
        $this->addSql('ALTER TABLE tag ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7832F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('DROP TABLE produit_tag');
    }
}
