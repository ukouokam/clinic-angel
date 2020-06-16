<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616005001 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lab_test_models_requested_details DROP CONSTRAINT fk_718dc327c07ea3de');
        $this->addSql('DROP INDEX idx_718dc327c07ea3de');
        $this->addSql('ALTER TABLE lab_test_models_requested_details DROP lab_test_model_id');
        $this->addSql('ALTER TABLE lab_test_models ALTER code DROP NOT NULL');
        $this->addSql('ALTER TABLE drug_posologies ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE drug_posologies ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE drug_posologies ADD CONSTRAINT FK_7A50F923B03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7A50F923B03A8386 ON drug_posologies (created_by_id)');
        $this->addSql('ALTER TABLE drug_forms DROP code');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lab_test_models ALTER code SET NOT NULL');
        $this->addSql('ALTER TABLE lab_test_models_requested_details ADD lab_test_model_id INT NOT NULL');
        $this->addSql('ALTER TABLE lab_test_models_requested_details ADD CONSTRAINT fk_718dc327c07ea3de FOREIGN KEY (lab_test_model_id) REFERENCES lab_test_models (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_718dc327c07ea3de ON lab_test_models_requested_details (lab_test_model_id)');
        $this->addSql('ALTER TABLE drug_posologies DROP CONSTRAINT FK_7A50F923B03A8386');
        $this->addSql('DROP INDEX IDX_7A50F923B03A8386');
        $this->addSql('ALTER TABLE drug_posologies DROP created_by_id');
        $this->addSql('ALTER TABLE drug_posologies DROP created_at');
        $this->addSql('ALTER TABLE drug_forms ADD code VARCHAR(255) NOT NULL');
    }
}
