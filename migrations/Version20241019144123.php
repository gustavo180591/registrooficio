<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019144123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, registro_id INT NOT NULL, calification INT NOT NULL, comment VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9474526C39C50FAE (registro_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delegacion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oficio (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recomendacion (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registro (id INT AUTO_INCREMENT NOT NULL, oficio_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, phone VARCHAR(255) NOT NULL, dni VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, work_address VARCHAR(255) DEFAULT NULL, payment VARCHAR(255) NOT NULL, time VARCHAR(255) NOT NULL, certification TINYINT(1) NOT NULL, institution VARCHAR(255) DEFAULT NULL, recomendation VARCHAR(255) DEFAULT NULL, images VARCHAR(255) DEFAULT NULL, INDEX IDX_397CA85B8D7E696E (oficio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registro_delegacion (registro_id INT NOT NULL, delegacion_id INT NOT NULL, INDEX IDX_EAAFB4E139C50FAE (registro_id), INDEX IDX_EAAFB4E1F4B21EB5 (delegacion_id), PRIMARY KEY(registro_id, delegacion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C39C50FAE FOREIGN KEY (registro_id) REFERENCES registro (id)');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B8D7E696E FOREIGN KEY (oficio_id) REFERENCES oficio (id)');
        $this->addSql('ALTER TABLE registro_delegacion ADD CONSTRAINT FK_EAAFB4E139C50FAE FOREIGN KEY (registro_id) REFERENCES registro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registro_delegacion ADD CONSTRAINT FK_EAAFB4E1F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C39C50FAE');
        $this->addSql('ALTER TABLE registro DROP FOREIGN KEY FK_397CA85B8D7E696E');
        $this->addSql('ALTER TABLE registro_delegacion DROP FOREIGN KEY FK_EAAFB4E139C50FAE');
        $this->addSql('ALTER TABLE registro_delegacion DROP FOREIGN KEY FK_EAAFB4E1F4B21EB5');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE delegacion');
        $this->addSql('DROP TABLE oficio');
        $this->addSql('DROP TABLE recomendacion');
        $this->addSql('DROP TABLE registro');
        $this->addSql('DROP TABLE registro_delegacion');
    }
}
