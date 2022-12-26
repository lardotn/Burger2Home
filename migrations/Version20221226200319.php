<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221226200319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE burger_category (burger_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(burger_id, category_id))');
        $this->addSql('CREATE INDEX IDX_C87F412517CE5090 ON burger_category (burger_id)');
        $this->addSql('CREATE INDEX IDX_C87F412512469DE2 ON burger_category (category_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE categories_burgers (category_id INT NOT NULL, burger_id INT NOT NULL, PRIMARY KEY(category_id, burger_id))');
        $this->addSql('CREATE INDEX IDX_4D5A814C12469DE2 ON categories_burgers (category_id)');
        $this->addSql('CREATE INDEX IDX_4D5A814C17CE5090 ON categories_burgers (burger_id)');
        $this->addSql('ALTER TABLE burger_category ADD CONSTRAINT FK_C87F412517CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE burger_category ADD CONSTRAINT FK_C87F412512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_burgers ADD CONSTRAINT FK_4D5A814C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories_burgers ADD CONSTRAINT FK_4D5A814C17CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('ALTER TABLE burger_category DROP CONSTRAINT FK_C87F412517CE5090');
        $this->addSql('ALTER TABLE burger_category DROP CONSTRAINT FK_C87F412512469DE2');
        $this->addSql('ALTER TABLE categories_burgers DROP CONSTRAINT FK_4D5A814C12469DE2');
        $this->addSql('ALTER TABLE categories_burgers DROP CONSTRAINT FK_4D5A814C17CE5090');
        $this->addSql('DROP TABLE burger_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE categories_burgers');
    }
}
