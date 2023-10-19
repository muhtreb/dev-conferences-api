<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018121723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // use postgresql syntax
        // add slug nullable field to conference_edition table
        $this->addSql('ALTER TABLE conference_edition ADD slug TEXT DEFAULT NULL');

        // Update slug field with slugify name
        $this->addSql(<<<SQL
            UPDATE conference_edition SET slug = lower(REPLACE(REPLACE(name, ' ', '-') , ':', '-'))
        SQL
        );

        // Set not nullable slug (postgresql)
        $this->addSql('ALTER TABLE conference_edition ALTER slug SET NOT NULL');

        // add unique index on slug field
        $this->addSql('CREATE UNIQUE INDEX conference_edition_slug_idx ON conference_edition (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference_edition DROP slug');
        // Remove index
        $this->addSql('DROP INDEX conference_edition_slug_idx');
    }
}
