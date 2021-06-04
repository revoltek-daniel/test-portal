<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210604131519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_answer DROP INDEX UNIQ_BF8F51181E27F6BF, ADD INDEX IDX_BF8F51181E27F6BF (question_id)');
        $this->addSql('ALTER TABLE user_answer DROP INDEX UNIQ_BF8F5118A76ED395, ADD INDEX IDX_BF8F5118A76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_answer DROP INDEX IDX_BF8F51181E27F6BF, ADD UNIQUE INDEX UNIQ_BF8F51181E27F6BF (question_id)');
        $this->addSql('ALTER TABLE user_answer DROP INDEX IDX_BF8F5118A76ED395, ADD UNIQUE INDEX UNIQ_BF8F5118A76ED395 (user_id)');
    }
}
