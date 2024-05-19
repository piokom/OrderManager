<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240519182334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE customer_order (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, total_price NUMERIC(10, 2) NOT NULL, total_vat NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE INDEX created_at_idx ON customer_order (created_at)');

        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, customer_order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, price NUMERIC(10, 2) NOT NULL, vat NUMERIC(10, 2) NOT NULL, INDEX IDX_52EA1F09A15A2E17 (customer_order_id), INDEX IDX_52EA1F094584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE INDEX customer_order_product_idx ON order_item (customer_order_id, product_id)');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, vat_rate INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE INDEX name_idx ON product (name)');

        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09A15A2E17 FOREIGN KEY (customer_order_id) REFERENCES customer_order (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09A15A2E17');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('DROP TABLE customer_order');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE product');
    }
}
