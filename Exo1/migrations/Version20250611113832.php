<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611113832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE notifs DROP FOREIGN KEY fk_user_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_user_id ON notifs
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notifs CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY fk_user_id_prodcut
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_user_id_prodcut ON product
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD description VARCHAR(255) NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users CHANGE points points VARCHAR(255) NOT NULL, CHANGE disabled disabled TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE notifs CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notifs ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_user_id ON notifs (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP description, CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT fk_user_id_prodcut FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_user_id_prodcut ON product (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users CHANGE points points INT NOT NULL, CHANGE disabled disabled TINYINT(1) DEFAULT 1 NOT NULL
        SQL);
    }
}
