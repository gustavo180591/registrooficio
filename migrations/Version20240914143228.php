<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240914143228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registro_delegacion (registro_id INT NOT NULL, delegacion_id INT NOT NULL, INDEX IDX_EAAFB4E139C50FAE (registro_id), INDEX IDX_EAAFB4E1F4B21EB5 (delegacion_id), PRIMARY KEY(registro_id, delegacion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registro_delegacion ADD CONSTRAINT FK_EAAFB4E139C50FAE FOREIGN KEY (registro_id) REFERENCES registro (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registro_delegacion ADD CONSTRAINT FK_EAAFB4E1F4B21EB5 FOREIGN KEY (delegacion_id) REFERENCES delegacion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registro ADD oficio_id INT NOT NULL');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B8D7E696E FOREIGN KEY (oficio_id) REFERENCES oficio (id)');
        $this->addSql('CREATE INDEX IDX_397CA85B8D7E696E ON registro (oficio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registro_delegacion DROP FOREIGN KEY FK_EAAFB4E139C50FAE');
        $this->addSql('ALTER TABLE registro_delegacion DROP FOREIGN KEY FK_EAAFB4E1F4B21EB5');
        $this->addSql('DROP TABLE registro_delegacion');
        $this->addSql('ALTER TABLE registro DROP FOREIGN KEY FK_397CA85B8D7E696E');
        $this->addSql('DROP INDEX IDX_397CA85B8D7E696E ON registro');
        $this->addSql('ALTER TABLE registro DROP oficio_id');
    }
}
