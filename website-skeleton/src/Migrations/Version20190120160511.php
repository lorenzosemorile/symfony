<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190120160511 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE product ADD COLUMN created DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD COLUMN modified DATETIME DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_E3A6E39C4584665A');
        $this->addSql('DROP INDEX IDX_E3A6E39CBAD26311');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_tag AS SELECT product_id, tag_id FROM product_tag');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('CREATE TABLE product_tag (product_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(product_id, tag_id), CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E3A6E39CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_tag (product_id, tag_id) SELECT product_id, tag_id FROM __temp__product_tag');
        $this->addSql('DROP TABLE __temp__product_tag');
        $this->addSql('CREATE INDEX IDX_E3A6E39C4584665A ON product_tag (product_id)');
        $this->addSql('CREATE INDEX IDX_E3A6E39CBAD26311 ON product_tag (tag_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, description, image FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description CLOB DEFAULT NULL, image VARCHAR(150) DEFAULT NULL)');
        $this->addSql('INSERT INTO product (id, name, description, image) SELECT id, name, description, image FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('DROP INDEX IDX_E3A6E39C4584665A');
        $this->addSql('DROP INDEX IDX_E3A6E39CBAD26311');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_tag AS SELECT product_id, tag_id FROM product_tag');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('CREATE TABLE product_tag (product_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(product_id, tag_id))');
        $this->addSql('INSERT INTO product_tag (product_id, tag_id) SELECT product_id, tag_id FROM __temp__product_tag');
        $this->addSql('DROP TABLE __temp__product_tag');
        $this->addSql('CREATE INDEX IDX_E3A6E39C4584665A ON product_tag (product_id)');
        $this->addSql('CREATE INDEX IDX_E3A6E39CBAD26311 ON product_tag (tag_id)');
    }
}
