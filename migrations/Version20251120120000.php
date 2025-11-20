<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251120120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Suppression de la colonne dtype de la table utilisateur';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE utilisateur DROP dtype');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE utilisateur ADD dtype VARCHAR(255) NOT NULL');
    }
}
