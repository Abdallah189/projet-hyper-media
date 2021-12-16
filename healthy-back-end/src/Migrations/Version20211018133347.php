<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211018133347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, birthday VARCHAR(50) DEFAULT NULL, gouvernorate VARCHAR(50) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, phone_number INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, survey_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, response VARCHAR(255) DEFAULT NULL, INDEX IDX_B6F7494EB3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE success_story (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, content LONGTEXT NOT NULL, accepted TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_7E423E4FB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, created_at VARCHAR(255) DEFAULT NULL, feedback VARCHAR(255) DEFAULT NULL, INDEX IDX_AD5F9BFCB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, from_doctor_id INT DEFAULT NULL, survey_id INT DEFAULT NULL, content VARCHAR(1000) DEFAULT NULL, INDEX IDX_DADD4A2597686EAD (from_doctor_id), UNIQUE INDEX UNIQ_DADD4A25B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EB3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE success_story ADD CONSTRAINT FK_7E423E4FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE survey ADD CONSTRAINT FK_AD5F9BFCB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A2597686EAD FOREIGN KEY (from_doctor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE success_story DROP FOREIGN KEY FK_7E423E4FB03A8386');
        $this->addSql('ALTER TABLE survey DROP FOREIGN KEY FK_AD5F9BFCB03A8386');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A2597686EAD');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EB3FE509D');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25B3FE509D');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE success_story');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE answer');
    }
}
