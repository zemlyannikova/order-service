<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231213203421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add initial data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO product (name) VALUES ('Product 1'), ('Product 2')");
        $this->addSql("INSERT INTO user () VALUES ()");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM product");
        $this->addSql("DELETE FROM user");
    }
}
