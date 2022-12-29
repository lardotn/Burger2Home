<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221229212607 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ingredient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE allergen (id INT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE burger (id INT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, description TEXT NOT NULL, base_price DOUBLE PRECISION NOT NULL, promo_price DOUBLE PRECISION NOT NULL, img_path VARCHAR(255) DEFAULT NULL, is_active BOOLEAN NOT NULL, burger_point INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EFE35A0D5E237E06 ON burger (name)');
        $this->addSql('CREATE TABLE burger_category (burger_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(burger_id, category_id))');
        $this->addSql('CREATE INDEX IDX_C87F412517CE5090 ON burger_category (burger_id)');
        $this->addSql('CREATE INDEX IDX_C87F412512469DE2 ON burger_category (category_id)');
        $this->addSql('CREATE TABLE burger_ingredient (burger_id INT NOT NULL, ingredient_id INT NOT NULL, PRIMARY KEY(burger_id, ingredient_id))');
        $this->addSql('CREATE INDEX IDX_340D596D17CE5090 ON burger_ingredient (burger_id)');
        $this->addSql('CREATE INDEX IDX_340D596D933FE08C ON burger_ingredient (ingredient_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ingredient (id INT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ingredient_allergen (ingredient_id INT NOT NULL, allergen_id INT NOT NULL, PRIMARY KEY(ingredient_id, allergen_id))');
        $this->addSql('CREATE INDEX IDX_57B95840933FE08C ON ingredient_allergen (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_57B958406E775A4A ON ingredient_allergen (allergen_id)');
        $this->addSql('ALTER TABLE burger_category ADD CONSTRAINT FK_C87F412517CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE burger_category ADD CONSTRAINT FK_C87F412512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE burger_ingredient ADD CONSTRAINT FK_340D596D17CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE burger_ingredient ADD CONSTRAINT FK_340D596D933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient_allergen ADD CONSTRAINT FK_57B95840933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient_allergen ADD CONSTRAINT FK_57B958406E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE allergen_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE burger_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ingredient_id_seq CASCADE');
        $this->addSql('ALTER TABLE burger_category DROP CONSTRAINT FK_C87F412517CE5090');
        $this->addSql('ALTER TABLE burger_category DROP CONSTRAINT FK_C87F412512469DE2');
        $this->addSql('ALTER TABLE burger_ingredient DROP CONSTRAINT FK_340D596D17CE5090');
        $this->addSql('ALTER TABLE burger_ingredient DROP CONSTRAINT FK_340D596D933FE08C');
        $this->addSql('ALTER TABLE ingredient_allergen DROP CONSTRAINT FK_57B95840933FE08C');
        $this->addSql('ALTER TABLE ingredient_allergen DROP CONSTRAINT FK_57B958406E775A4A');
        $this->addSql('DROP TABLE allergen');
        $this->addSql('DROP TABLE burger');
        $this->addSql('DROP TABLE burger_category');
        $this->addSql('DROP TABLE burger_ingredient');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_allergen');
    }
}
