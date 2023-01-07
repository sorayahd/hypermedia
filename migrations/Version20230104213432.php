<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104213432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_details (cart_id INT NOT NULL, articles_id INT NOT NULL, quantity INT NOT NULL, price INT NOT NULL, INDEX IDX_89FCC38D1AD5CDBF (cart_id), INDEX IDX_89FCC38D1EBAF6CC (articles_id), PRIMARY KEY(cart_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_details ADD CONSTRAINT FK_89FCC38D1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_details ADD CONSTRAINT FK_89FCC38D1EBAF6CC FOREIGN KEY (articles_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE cart_detail DROP FOREIGN KEY FK_20821DCC1AD5CDBF');
        $this->addSql('DROP TABLE cart_detail');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_detail (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, nom_article VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prix_article DOUBLE PRECISION NOT NULL, quantity_article INT NOT NULL, total_produit DOUBLE PRECISION NOT NULL, INDEX IDX_20821DCC1AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_detail ADD CONSTRAINT FK_20821DCC1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_details DROP FOREIGN KEY FK_89FCC38D1AD5CDBF');
        $this->addSql('ALTER TABLE cart_details DROP FOREIGN KEY FK_89FCC38D1EBAF6CC');
        $this->addSql('DROP TABLE cart_details');
    }
}
