<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200401130749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(128) NOT NULL, poster VARCHAR(255) NOT NULL, country VARCHAR(128) NOT NULL, rated VARCHAR(32) NOT NULL, runtime SMALLINT UNSIGNED DEFAULT 0 NOT NULL, released DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , global_rating_value NUMERIC(2, 1) NOT NULL, price NUMERIC(5, 2) NOT NULL, description CLOB NOT NULL, directors CLOB NOT NULL --(DC2Type:array)
        , writers CLOB NOT NULL --(DC2Type:array)
        , actors CLOB NOT NULL --(DC2Type:array)
        , awards VARCHAR(255) NOT NULL, production VARCHAR(128) NOT NULL, ratings CLOB NOT NULL --(DC2Type:array)
        , trailers CLOB NOT NULL --(DC2Type:array)
        , photos CLOB NOT NULL --(DC2Type:array)
        , is_active BOOLEAN DEFAULT \'1\' NOT NULL, uuid CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D5EF26FD17F50A6 ON movie (uuid)');
        $this->addSql('CREATE TABLE movie_genre (movie_id INTEGER UNSIGNED NOT NULL, genre_id INTEGER UNSIGNED NOT NULL, PRIMARY KEY(movie_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_FD1229648F93B6FC ON movie_genre (movie_id)');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
        $this->addSql('CREATE TABLE genre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(64) NOT NULL, poster VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, uuid CHAR(36) NOT NULL --(DC2Type:uuid)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_835033F85E237E06 ON genre (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_835033F8D17F50A6 ON genre (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('DROP TABLE genre');
    }
}
