<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730155105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE presenca (id INT AUTO_INCREMENT NOT NULL, aluno_id INT DEFAULT NULL, aula_id INT DEFAULT NULL, presente TINYINT(1) NOT NULL, INDEX IDX_6E1A03BCB2DDF7F4 (aluno_id), INDEX IDX_6E1A03BCAD1A1255 (aula_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE presenca ADD CONSTRAINT FK_6E1A03BCB2DDF7F4 FOREIGN KEY (aluno_id) REFERENCES aluno (id)');
        $this->addSql('ALTER TABLE presenca ADD CONSTRAINT FK_6E1A03BCAD1A1255 FOREIGN KEY (aula_id) REFERENCES aula (id)');
        $this->addSql('ALTER TABLE aluno ADD nome VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presenca DROP FOREIGN KEY FK_6E1A03BCB2DDF7F4');
        $this->addSql('ALTER TABLE presenca DROP FOREIGN KEY FK_6E1A03BCAD1A1255');
        $this->addSql('DROP TABLE presenca');
        $this->addSql('ALTER TABLE aluno DROP nome');
    }
}
