<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616114955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE blood_groups ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE blood_groups ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE blood_groups ADD CONSTRAINT FK_86D78E43B03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_86D78E43B03A8386 ON blood_groups (created_by_id)');
        $this->addSql('ALTER TABLE nurses_categories ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE nurses_categories ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE nurses_categories ADD CONSTRAINT FK_14F2F03CB03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_14F2F03CB03A8386 ON nurses_categories (created_by_id)');
        $this->addSql('ALTER TABLE doctors_categories ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE doctors_categories ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE doctors_categories ADD CONSTRAINT FK_C3BC9492B03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C3BC9492B03A8386 ON doctors_categories (created_by_id)');
        $this->addSql('ALTER TABLE others_types_staff ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE others_types_staff ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE others_types_staff ADD CONSTRAINT FK_90339784B03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90339784B03A8386 ON others_types_staff (created_by_id)');
        $this->addSql('ALTER TABLE technicians_categories ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE technicians_categories ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE technicians_categories ADD CONSTRAINT FK_5F78827DB03A8386 FOREIGN KEY (created_by_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5F78827DB03A8386 ON technicians_categories (created_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE blood_groups DROP CONSTRAINT FK_86D78E43B03A8386');
        $this->addSql('DROP INDEX IDX_86D78E43B03A8386');
        $this->addSql('ALTER TABLE blood_groups DROP created_by_id');
        $this->addSql('ALTER TABLE blood_groups DROP created_at');
        $this->addSql('ALTER TABLE nurses_categories DROP CONSTRAINT FK_14F2F03CB03A8386');
        $this->addSql('DROP INDEX IDX_14F2F03CB03A8386');
        $this->addSql('ALTER TABLE nurses_categories DROP created_by_id');
        $this->addSql('ALTER TABLE nurses_categories DROP created_at');
        $this->addSql('ALTER TABLE doctors_categories DROP CONSTRAINT FK_C3BC9492B03A8386');
        $this->addSql('DROP INDEX IDX_C3BC9492B03A8386');
        $this->addSql('ALTER TABLE doctors_categories DROP created_by_id');
        $this->addSql('ALTER TABLE doctors_categories DROP created_at');
        $this->addSql('ALTER TABLE others_types_staff DROP CONSTRAINT FK_90339784B03A8386');
        $this->addSql('DROP INDEX IDX_90339784B03A8386');
        $this->addSql('ALTER TABLE others_types_staff DROP created_by_id');
        $this->addSql('ALTER TABLE others_types_staff DROP created_at');
        $this->addSql('ALTER TABLE technicians_categories DROP CONSTRAINT FK_5F78827DB03A8386');
        $this->addSql('DROP INDEX IDX_5F78827DB03A8386');
        $this->addSql('ALTER TABLE technicians_categories DROP created_by_id');
        $this->addSql('ALTER TABLE technicians_categories DROP created_at');
    }
}
