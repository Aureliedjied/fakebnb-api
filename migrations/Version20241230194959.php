<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230194959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amenity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE amenity_property (amenity_id INT NOT NULL, property_id INT NOT NULL, INDEX IDX_8BAEC3AC9F9F1305 (amenity_id), INDEX IDX_8BAEC3AC549213EC (property_id), PRIMARY KEY(amenity_id, property_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amenity_property ADD CONSTRAINT FK_8BAEC3AC9F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE amenity_property ADD CONSTRAINT FK_8BAEC3AC549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE amenity_property DROP FOREIGN KEY FK_8BAEC3AC9F9F1305');
        $this->addSql('ALTER TABLE amenity_property DROP FOREIGN KEY FK_8BAEC3AC549213EC');
        $this->addSql('DROP TABLE amenity');
        $this->addSql('DROP TABLE amenity_property');
    }
}
