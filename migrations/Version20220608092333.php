<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608092333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere_produit (matiere_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_4A8363DBF46CD258 (matiere_id), INDEX IDX_4A8363DBF347EFB (produit_id), PRIMARY KEY(matiere_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matiere_produit ADD CONSTRAINT FK_4A8363DBF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_produit ADD CONSTRAINT FK_4A8363DBF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE produit_matiere');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_matiere (produit_id INT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_56EFDC33F46CD258 (matiere_id), INDEX IDX_56EFDC33F347EFB (produit_id), PRIMARY KEY(produit_id, matiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produit_matiere ADD CONSTRAINT FK_56EFDC33F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_matiere ADD CONSTRAINT FK_56EFDC33F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE matiere_produit');
    }
}
