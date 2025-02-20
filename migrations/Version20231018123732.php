<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018123732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // use postgresql syntax
        // add slug nullable field to conference_edition table
        $this->addSql('ALTER TABLE talk ADD slug TEXT DEFAULT NULL');

        // Update slug field with slugify name
        $this->addSql(<<<SQL
            UPDATE talk SET slug = concat(lower(REPLACE(REPLACE(name, ' ', '-') , ':', '-')), '-', id)
        SQL
        );

        // Set not nullable slug (postgresql)
        $this->addSql('ALTER TABLE talk ALTER slug SET NOT NULL');

        // add unique index on slug field
        $this->addSql('CREATE UNIQUE INDEX talk_slug_idx ON talk (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE talk DROP slug');
        // Remove index
        $this->addSql('DROP INDEX talk_slug_idx');
    }
}
