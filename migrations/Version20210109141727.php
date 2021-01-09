<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210109141727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_route_passenger (route_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(route_id, user_id))');
        $this->addSql('CREATE INDEX IDX_8571515A34ECB4E6 ON user_route_passenger (route_id)');
        $this->addSql('CREATE INDEX IDX_8571515AA76ED395 ON user_route_passenger (user_id)');
        $this->addSql('ALTER TABLE user_route_passenger ADD CONSTRAINT FK_8571515A34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_route_passenger ADD CONSTRAINT FK_8571515AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE route_user');
        $this->addSql('ALTER TABLE route ALTER repeat DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE route_user (route_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(route_id, user_id))');
        $this->addSql('CREATE INDEX idx_20d6d7cca76ed395 ON route_user (user_id)');
        $this->addSql('CREATE INDEX idx_20d6d7cc34ecb4e6 ON route_user (route_id)');
        $this->addSql('ALTER TABLE route_user ADD CONSTRAINT fk_20d6d7cc34ecb4e6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE route_user ADD CONSTRAINT fk_20d6d7cca76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE user_route_passenger');
        $this->addSql('ALTER TABLE route ALTER repeat SET DEFAULT 0');
    }
}
