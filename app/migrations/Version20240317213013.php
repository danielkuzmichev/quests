<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317213013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_task (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_task_user (user_task_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FEC0CFD6D5BB1F8C (user_task_id), INDEX IDX_FEC0CFD6A76ED395 (user_id), PRIMARY KEY(user_task_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_task_task (user_task_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_212DC2BAD5BB1F8C (user_task_id), INDEX IDX_212DC2BA8DB60186 (task_id), PRIMARY KEY(user_task_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_task_user ADD CONSTRAINT FK_FEC0CFD6D5BB1F8C FOREIGN KEY (user_task_id) REFERENCES user_task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_task_user ADD CONSTRAINT FK_FEC0CFD6A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_task_task ADD CONSTRAINT FK_212DC2BAD5BB1F8C FOREIGN KEY (user_task_id) REFERENCES user_task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_task_task ADD CONSTRAINT FK_212DC2BA8DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_task_user DROP FOREIGN KEY FK_FEC0CFD6D5BB1F8C');
        $this->addSql('ALTER TABLE user_task_user DROP FOREIGN KEY FK_FEC0CFD6A76ED395');
        $this->addSql('ALTER TABLE user_task_task DROP FOREIGN KEY FK_212DC2BAD5BB1F8C');
        $this->addSql('ALTER TABLE user_task_task DROP FOREIGN KEY FK_212DC2BA8DB60186');
        $this->addSql('DROP TABLE user_task');
        $this->addSql('DROP TABLE user_task_user');
        $this->addSql('DROP TABLE user_task_task');
    }
}
