<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231174452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amenity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_amenity (property_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_F2AD331B549213EC (property_id), INDEX IDX_F2AD331B9F9F1305 (amenity_id), PRIMARY KEY(property_id, amenity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_amenity ADD CONSTRAINT FK_F2AD331B549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_amenity ADD CONSTRAINT FK_F2AD331B9F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_amenities DROP FOREIGN KEY FK_9A9F56CA549213EC');
        $this->addSql('ALTER TABLE property_amenities DROP FOREIGN KEY FK_9A9F56CAB92D5262');
        $this->addSql('DROP TABLE amenities');
        $this->addSql('DROP TABLE property_amenities');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amenities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE property_amenities (property_id INT NOT NULL, amenities_id INT NOT NULL, INDEX IDX_9A9F56CAB92D5262 (amenities_id), INDEX IDX_9A9F56CA549213EC (property_id), PRIMARY KEY(property_id, amenities_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE property_amenities ADD CONSTRAINT FK_9A9F56CA549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_amenities ADD CONSTRAINT FK_9A9F56CAB92D5262 FOREIGN KEY (amenities_id) REFERENCES amenities (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_amenity DROP FOREIGN KEY FK_F2AD331B549213EC');
        $this->addSql('ALTER TABLE property_amenity DROP FOREIGN KEY FK_F2AD331B9F9F1305');
        $this->addSql('DROP TABLE amenity');
        $this->addSql('DROP TABLE property_amenity');
    }
}
