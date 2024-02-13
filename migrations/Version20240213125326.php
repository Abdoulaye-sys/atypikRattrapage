<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213125326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebergement ADD property_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CB9575F5A FOREIGN KEY (property_id_id) REFERENCES property (id)');
        $this->addSql('CREATE INDEX IDX_4852DD9CB9575F5A ON hebergement (property_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CB9575F5A');
        $this->addSql('DROP INDEX IDX_4852DD9CB9575F5A ON hebergement');
        $this->addSql('ALTER TABLE hebergement DROP property_id_id');
    }
}
