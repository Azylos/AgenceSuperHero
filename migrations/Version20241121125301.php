<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121125301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE super_hero CHANGE is_available avaiable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE team CHANGE is_active active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team CHANGE active is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE super_hero CHANGE avaiable is_available TINYINT(1) NOT NULL');
    }
}
