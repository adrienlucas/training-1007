<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713103402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $result = $this->connection->query('SELECT id from user limit 1');
        $defaultUserId = $result->fetch()['id'];

        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, plot, released_at FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_by_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, plot CLOB DEFAULT NULL, released_at DATE DEFAULT NULL, CONSTRAINT FK_1D5EF26FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movie (id, title, plot, created_by_id, released_at) SELECT id, title, plot, '.$defaultUserId.', released_at FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE INDEX IDX_1D5EF26FB03A8386 ON movie (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, title, plot, released_at FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, plot CLOB DEFAULT NULL, released_at DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO movie (id, title, plot, released_at) SELECT id, title, plot, released_at FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
    }
}
