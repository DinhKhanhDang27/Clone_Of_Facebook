<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809070804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD profile_picture VARCHAR(255) DEFAULT NULL, ADD cover_photo VARCHAR(255) DEFAULT NULL, ADD relationship_status VARCHAR(255) DEFAULT NULL, ADD work VARCHAR(255) DEFAULT NULL, ADD education VARCHAR(255) DEFAULT NULL, ADD contact_info VARCHAR(255) DEFAULT NULL, ADD about LONGTEXT DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP profile_picture, DROP cover_photo, DROP relationship_status, DROP work, DROP education, DROP contact_info, DROP about, DROP is_verified');
    }
}
