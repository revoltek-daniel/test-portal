<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608094623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, INDEX IDX_136AC113A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result_step (id INT AUTO_INCREMENT NOT NULL, step_id INT NOT NULL, result_id INT NOT NULL, total INT NOT NULL, correct INT NOT NULL, processed INT NOT NULL, INDEX IDX_D382E57273B21E9C (step_id), INDEX IDX_D382E5727A7B643 (result_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE result_step ADD CONSTRAINT FK_D382E57273B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('ALTER TABLE result_step ADD CONSTRAINT FK_D382E5727A7B643 FOREIGN KEY (result_id) REFERENCES result (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result_step DROP FOREIGN KEY FK_D382E5727A7B643');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE result_step');
    }
}