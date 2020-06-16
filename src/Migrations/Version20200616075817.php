<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616075817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE civilities ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE civilities ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE civilities ADD CONSTRAINT FK_C442D3C6B03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C442D3C6B03A8386 ON civilities (created_by_id)');
        $this->addSql('ALTER TABLE marital_status ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE marital_status ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE marital_status ADD CONSTRAINT FK_F6B06AA8B03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6B06AA85E237E06 ON marital_status (name)');
        $this->addSql('CREATE INDEX IDX_F6B06AA8B03A8386 ON marital_status (created_by_id)');
        $this->addSql('ALTER TABLE sexes ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE sexes ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE sexes ADD CONSTRAINT FK_FFCCECBFB03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FFCCECBFB03A8386 ON sexes (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sexes DROP CONSTRAINT FK_FFCCECBFB03A8386');
        $this->addSql('DROP INDEX IDX_FFCCECBFB03A8386');
        $this->addSql('ALTER TABLE sexes DROP created_by_id');
        $this->addSql('ALTER TABLE sexes DROP created_at');
        $this->addSql('ALTER TABLE civilities DROP CONSTRAINT FK_C442D3C6B03A8386');
        $this->addSql('DROP INDEX IDX_C442D3C6B03A8386');
        $this->addSql('ALTER TABLE civilities DROP created_by_id');
        $this->addSql('ALTER TABLE civilities DROP created_at');
        $this->addSql('ALTER TABLE marital_status DROP CONSTRAINT FK_F6B06AA8B03A8386');
        $this->addSql('DROP INDEX UNIQ_F6B06AA85E237E06');
        $this->addSql('DROP INDEX IDX_F6B06AA8B03A8386');
        $this->addSql('ALTER TABLE marital_status DROP created_by_id');
        $this->addSql('ALTER TABLE marital_status DROP created_at');
    }
}
