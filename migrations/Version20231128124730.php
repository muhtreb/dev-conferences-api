<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231128124730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speaker RENAME COLUMN twitter TO x_username ');
        $this->addSql('ALTER TABLE speaker RENAME COLUMN github TO github_username');
        $this->addSql('ALTER TABLE speaker RENAME COLUMN speaker_deck TO speaker_deck_username');
        $this->addSql('ALTER TABLE speaker ADD bluesky_username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE speaker ADD mastodon_username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE speaker DROP legacy_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speaker RENAME COLUMN x_username TO twitter ');
        $this->addSql('ALTER TABLE speaker RENAME COLUMN github_username TO github');
        $this->addSql('ALTER TABLE speaker RENAME COLUMN speaker_deck_username TO speaker_deck');
        $this->addSql('ALTER TABLE speaker DROP bluesky_username');
        $this->addSql('ALTER TABLE speaker DROP mastodon_username');
        $this->addSql('ALTER TABLE speaker ADD legacy_id INT DEFAULT NULL');
    }
}
