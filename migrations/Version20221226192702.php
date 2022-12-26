<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221226192702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE allergen_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE burger_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE allergen (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE burger (id INT NOT NULL, name VARCHAR(50) NOT NULL, description TEXT NOT NULL, price DOUBLE PRECISION NOT NULL, img_url VARCHAR(255) DEFAULT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, name VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE allergen_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE burger_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('DROP TABLE allergen');
        $this->addSql('DROP TABLE burger');
        $this->addSql('DROP TABLE ingredient');
    }
}
