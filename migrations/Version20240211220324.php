<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211220324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D3256915B');
        $this->addSql('DROP TABLE payment');
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9C4C3A3BB');
        $this->addSql('DROP INDEX IDX_4852DD9C4C3A3BB ON hebergement');
        $this->addSql('ALTER TABLE hebergement DROP payment_id, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE date_disponibilite date_disponibilite DATE NOT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, relation_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_6D28840D3256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D3256915B FOREIGN KEY (relation_id) REFERENCES hebergement (id)');
        $this->addSql('ALTER TABLE hebergement ADD payment_id INT DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE date_disponibilite date_disponibilite DATE DEFAULT NULL, CHANGE prix prix NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9C4C3A3BB FOREIGN KEY (payment_id) REFERENCES property (id)');
        $this->addSql('CREATE INDEX IDX_4852DD9C4C3A3BB ON hebergement (payment_id)');
    }
}
