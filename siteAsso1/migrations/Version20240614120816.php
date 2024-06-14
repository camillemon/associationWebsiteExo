<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614120816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, city VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, participants VARCHAR(255) DEFAULT NULL, volunteer VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteers_events (volunteers_id INT NOT NULL, events_id INT NOT NULL, INDEX IDX_DA85D8E85B99C489 (volunteers_id), INDEX IDX_DA85D8E89D6A1065 (events_id), PRIMARY KEY(volunteers_id, events_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE volunteers_events ADD CONSTRAINT FK_DA85D8E85B99C489 FOREIGN KEY (volunteers_id) REFERENCES volunteers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volunteers_events ADD CONSTRAINT FK_DA85D8E89D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteers_events DROP FOREIGN KEY FK_DA85D8E85B99C489');
        $this->addSql('ALTER TABLE volunteers_events DROP FOREIGN KEY FK_DA85D8E89D6A1065');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE volunteers');
        $this->addSql('DROP TABLE volunteers_events');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
