<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025134558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_favorite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_favorite (id INT NOT NULL, user_id UUID NOT NULL, conference_id UUID NOT NULL, conference_edition_id UUID NOT NULL, speaker_id UUID NOT NULL, talk_id UUID NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_88486AD9A76ED395 ON user_favorite (user_id)');
        $this->addSql('CREATE INDEX IDX_88486AD9604B8382 ON user_favorite (conference_id)');
        $this->addSql('CREATE INDEX IDX_88486AD98AB0AD79 ON user_favorite (conference_edition_id)');
        $this->addSql('CREATE INDEX IDX_88486AD9D04A0F27 ON user_favorite (speaker_id)');
        $this->addSql('CREATE INDEX IDX_88486AD96F0601D5 ON user_favorite (talk_id)');
        $this->addSql('COMMENT ON COLUMN user_favorite.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_favorite.conference_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_favorite.conference_edition_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_favorite.speaker_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_favorite.talk_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD9A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD9604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD98AB0AD79 FOREIGN KEY (conference_edition_id) REFERENCES conference_edition (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD9D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD96F0601D5 FOREIGN KEY (talk_id) REFERENCES talk (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE user_favorite_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_favorite DROP CONSTRAINT FK_88486AD9A76ED395');
        $this->addSql('ALTER TABLE user_favorite DROP CONSTRAINT FK_88486AD9604B8382');
        $this->addSql('ALTER TABLE user_favorite DROP CONSTRAINT FK_88486AD98AB0AD79');
        $this->addSql('ALTER TABLE user_favorite DROP CONSTRAINT FK_88486AD9D04A0F27');
        $this->addSql('ALTER TABLE user_favorite DROP CONSTRAINT FK_88486AD96F0601D5');
        $this->addSql('DROP TABLE user_favorite');
    }
}
