<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227102405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wish_list (id INT AUTO_INCREMENT NOT NULL, product_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, wishlist_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_5B8739BDDE18E50B (product_id_id), INDEX IDX_5B8739BD9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BD9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product CHANGE product_price product_price NUMERIC(10, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BDDE18E50B');
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BD9D86650F');
        $this->addSql('DROP TABLE wish_list');
        $this->addSql('ALTER TABLE product CHANGE product_price product_price DOUBLE PRECISION NOT NULL');
    }
}
