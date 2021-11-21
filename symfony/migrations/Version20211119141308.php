<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211119141308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /* public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurent ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE restaurent ADD CONSTRAINT FK_EC9CBAE3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EC9CBAE3A76ED395 ON restaurent (user_id)');
    } */

    public function up(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE restaurent DROP FOREIGN KEY FK_EC9CBAE3A76ED395');
        $this->addSql('DROP INDEX IDX_EC9CBAE3A76ED395 ON restaurent');
        $this->addSql('ALTER TABLE restaurent DROP user_id');
    }
}
