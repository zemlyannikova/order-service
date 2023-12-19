<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213193357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init database schema';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE `order` (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
                user_id BIGINT UNSIGNED DEFAULT NULL,
                status VARCHAR(255) NOT NULL,
                user_name VARCHAR(255) DEFAULT NULL,
                user_address VARCHAR(255) DEFAULT NULL,
                user_phone VARCHAR(255) DEFAULT NULL,
                user_email VARCHAR(255) DEFAULT NULL,
                user_tax_number VARCHAR(255) DEFAULT NULL,
                INDEX IDX_68D82365A76ED395 (user_id),
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE order_product (
                order_id BIGINT UNSIGNED NOT NULL,
                product_id BIGINT UNSIGNED NOT NULL,
                quantity INT NOT NULL,
                INDEX IDX_2530ADE68D9F6D38 (order_id),
                INDEX IDX_2530ADE64584665A (product_id),
                PRIMARY KEY(order_id, product_id)) 
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE product (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE user (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) DEFAULT NULL,
                phone VARCHAR(255) DEFAULT NULL,
                email VARCHAR(255) DEFAULT NULL,
                address VARCHAR(255) DEFAULT NULL,
                tax_number VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            ALTER TABLE `order` 
                ADD CONSTRAINT FK_68D82365A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        ');

        $this->addSql('
            ALTER TABLE order_product 
                ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)
        ');

        $this->addSql('
            ALTER TABLE order_product 
                ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id)
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_68D82365A76ED395');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE68D9F6D38');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE64584665A');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
    }
}
