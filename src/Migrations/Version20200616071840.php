<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616071840 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE health_parameters ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE health_parameters ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE health_parameters ADD CONSTRAINT FK_18A33C3DB03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_18A33C3DB03A8386 ON health_parameters (created_by_id)');
        $this->addSql('ALTER TABLE validities_consultation ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE validities_consultation ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE validities_consultation ADD CONSTRAINT FK_52089EEB03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_52089EEB03A8386 ON validities_consultation (created_by_id)');
        $this->addSql('ALTER TABLE consultation_requested_parameters ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE consultation_requested_parameters ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE consultation_requested_parameters ADD CONSTRAINT FK_884E083FB03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_884E083FB03A8386 ON consultation_requested_parameters (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE validities_consultation DROP CONSTRAINT FK_52089EEB03A8386');
        $this->addSql('DROP INDEX IDX_52089EEB03A8386');
        $this->addSql('ALTER TABLE validities_consultation DROP created_by_id');
        $this->addSql('ALTER TABLE validities_consultation DROP created_at');
        $this->addSql('ALTER TABLE consultation_requested_parameters DROP CONSTRAINT FK_884E083FB03A8386');
        $this->addSql('DROP INDEX IDX_884E083FB03A8386');
        $this->addSql('ALTER TABLE consultation_requested_parameters DROP created_by_id');
        $this->addSql('ALTER TABLE consultation_requested_parameters DROP created_at');
        $this->addSql('ALTER TABLE health_parameters DROP CONSTRAINT FK_18A33C3DB03A8386');
        $this->addSql('DROP INDEX IDX_18A33C3DB03A8386');
        $this->addSql('ALTER TABLE health_parameters DROP created_by_id');
        $this->addSql('ALTER TABLE health_parameters DROP created_at');
    }
}
