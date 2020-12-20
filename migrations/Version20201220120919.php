<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220120919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE points_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE route_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE route (id INT NOT NULL, driver_id INT NOT NULL, source geometry(POINT, 4326) NOT NULL, target geometry(POINT, 4326) NOT NULL, way geometry(MULTILINESTRING, 4326) NOT NULL, seats BIGINT NOT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, zachadzka DOUBLE PRECISION NOT NULL, area geometry(POLYGON, 4326) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2C42079C3423909 ON route (driver_id)');
        $this->addSql('CREATE TABLE route_user (route_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(route_id, user_id))');
        $this->addSql('CREATE INDEX IDX_20D6D7CC34ECB4E6 ON route_user (route_id)');
        $this->addSql('CREATE INDEX IDX_20D6D7CCA76ED395 ON route_user (user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, car BOOLEAN NOT NULL, meno VARCHAR(255) NOT NULL, priezvisko VARCHAR(255) NOT NULL, car_description VARCHAR(255) DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079C3423909 FOREIGN KEY (driver_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE route_user ADD CONSTRAINT FK_20D6D7CC34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE route_user ADD CONSTRAINT FK_20D6D7CCA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE speed');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE route_user DROP CONSTRAINT FK_20D6D7CC34ECB4E6');
        $this->addSql('ALTER TABLE route DROP CONSTRAINT FK_2C42079C3423909');
        $this->addSql('ALTER TABLE route_user DROP CONSTRAINT FK_20D6D7CCA76ED395');
        $this->addSql('DROP SEQUENCE route_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE points_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE speed (highway TEXT DEFAULT NULL, priority DOUBLE PRECISION DEFAULT NULL, speed DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE route_user');
        $this->addSql('DROP TABLE "user"');
    }
}
