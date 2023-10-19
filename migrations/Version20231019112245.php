<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019112245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speaker ADD slug TEXT DEFAULT NULL');

        // Update slug field with slugify name
        $this->addSql(<<<SQL
            UPDATE speaker SET slug = concat(lower(REPLACE(REPLACE(first_name, ' ', '-') , ':', '-')), '-', lower(REPLACE(REPLACE(last_name, ' ', '-') , ':', '-')), '-', id)
        SQL
        );

        // Set not nullable slug (postgresql)
        $this->addSql('ALTER TABLE speaker ALTER slug SET NOT NULL');

        // add unique index on slug field
        $this->addSql('CREATE UNIQUE INDEX speaker_slug_idx ON speaker (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speaker DROP slug');
        // Remove index
        $this->addSql('DROP INDEX speaker_slug_idx');
    }
}
