<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200522143019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE field_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE field (id INT NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, title VARCHAR(255) NOT NULL, introduction TEXT NOT NULL, content_1 TEXT NOT NULL, content_2 TEXT NOT NULL, content_3 TEXT NOT NULL, main_image VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project_image (project_id INT NOT NULL, image_id INT NOT NULL, PRIMARY KEY(project_id, image_id))');
        $this->addSql('CREATE INDEX IDX_D6680DC1166D1F9C ON project_image (project_id)');
        $this->addSql('CREATE INDEX IDX_D6680DC13DA5256D ON project_image (image_id)');
        $this->addSql('CREATE TABLE project_field (project_id INT NOT NULL, field_id INT NOT NULL, PRIMARY KEY(project_id, field_id))');
        $this->addSql('CREATE INDEX IDX_48A04CC6166D1F9C ON project_field (project_id)');
        $this->addSql('CREATE INDEX IDX_48A04CC6443707B0 ON project_field (field_id)');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE project_image ADD CONSTRAINT FK_D6680DC1166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_image ADD CONSTRAINT FK_D6680DC13DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_field ADD CONSTRAINT FK_48A04CC6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_field ADD CONSTRAINT FK_48A04CC6443707B0 FOREIGN KEY (field_id) REFERENCES field (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_field DROP CONSTRAINT FK_48A04CC6443707B0');
        $this->addSql('ALTER TABLE project_image DROP CONSTRAINT FK_D6680DC1166D1F9C');
        $this->addSql('ALTER TABLE project_field DROP CONSTRAINT FK_48A04CC6166D1F9C');
        $this->addSql('ALTER TABLE project_image DROP CONSTRAINT FK_D6680DC13DA5256D');
        $this->addSql('DROP SEQUENCE field_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_image');
        $this->addSql('DROP TABLE project_field');
        $this->addSql('DROP TABLE image');
    }
}
