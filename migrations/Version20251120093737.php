<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120093737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_tag RENAME INDEX idx_3c7a5a5cf347efb TO IDX_423DC0FAF347EFB');
        $this->addSql('ALTER TABLE produit_tag RENAME INDEX idx_3c7a5a5cbad26311 TO IDX_423DC0FABAD26311');
        $this->addSql('ALTER TABLE utilisateur ADD dtype VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('ALTER TABLE produit_tag RENAME INDEX idx_423dc0faf347efb TO IDX_3C7A5A5CF347EFB');
        $this->addSql('ALTER TABLE produit_tag RENAME INDEX idx_423dc0fabad26311 TO IDX_3C7A5A5CBAD26311');
        $this->addSql('ALTER TABLE utilisateur DROP dtype');
    }
}
