<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120131215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurent_user (restaurent_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CBCF48D72A763278 (restaurent_id), INDEX IDX_CBCF48D7A76ED395 (user_id), PRIMARY KEY(restaurent_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE restaurent_user ADD CONSTRAINT FK_CBCF48D72A763278 FOREIGN KEY (restaurent_id) REFERENCES restaurent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurent_user ADD CONSTRAINT FK_CBCF48D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_restaurent');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_restaurent (user_id INT NOT NULL, restaurent_id INT NOT NULL, INDEX IDX_4BFB7C0F2A763278 (restaurent_id), INDEX IDX_4BFB7C0FA76ED395 (user_id), PRIMARY KEY(user_id, restaurent_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_restaurent ADD CONSTRAINT FK_4BFB7C0F2A763278 FOREIGN KEY (restaurent_id) REFERENCES restaurent (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_restaurent ADD CONSTRAINT FK_4BFB7C0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE restaurent_user');
    }
}
