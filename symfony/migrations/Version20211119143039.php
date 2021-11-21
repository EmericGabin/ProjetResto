<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211119143039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_restaurent (user_id INT NOT NULL, restaurent_id INT NOT NULL, INDEX IDX_4BFB7C0FA76ED395 (user_id), INDEX IDX_4BFB7C0F2A763278 (restaurent_id), PRIMARY KEY(user_id, restaurent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_restaurent ADD CONSTRAINT FK_4BFB7C0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_restaurent ADD CONSTRAINT FK_4BFB7C0F2A763278 FOREIGN KEY (restaurent_id) REFERENCES restaurent (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_restaurent');
    }
}
