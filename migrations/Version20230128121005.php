<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128121005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_user (product_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_7BF4E84584665A (product_id), INDEX IDX_7BF4E8A76ED395 (user_id), PRIMARY KEY(product_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE style (id INT AUTO_INCREMENT NOT NULL, style_name VARCHAR(255) NOT NULL, style_description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E84584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_user ADD CONSTRAINT FK_7BF4E8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD style_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBACD6074 FOREIGN KEY (style_id) REFERENCES style (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADBACD6074 ON product (style_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBACD6074');
        $this->addSql('ALTER TABLE product_user DROP FOREIGN KEY FK_7BF4E84584665A');
        $this->addSql('ALTER TABLE product_user DROP FOREIGN KEY FK_7BF4E8A76ED395');
        $this->addSql('DROP TABLE product_user');
        $this->addSql('DROP TABLE style');
        $this->addSql('DROP INDEX IDX_D34A04ADBACD6074 ON product');
        $this->addSql('ALTER TABLE product DROP style_id');
    }
}
