<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200613195116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C81E852D4E6F81 ON emails (address)');
        $this->addSql('ALTER TABLE persons ALTER slug DROP NOT NULL');
        $this->addSql('ALTER TABLE users ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9B03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1483A5E9B03A8386 ON users (created_by_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FFCCECBFBC265CF4 ON sexes (sex_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE persons ALTER slug SET NOT NULL');
        $this->addSql('DROP INDEX UNIQ_4C81E852D4E6F81');
        $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_1483A5E9B03A8386');
        $this->addSql('DROP INDEX IDX_1483A5E9B03A8386');
        $this->addSql('ALTER TABLE "users" DROP created_by_id');
        $this->addSql('DROP INDEX UNIQ_FFCCECBFBC265CF4');
    }
}
