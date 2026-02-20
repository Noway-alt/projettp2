<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260219160753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quiz_attempt (id INT AUTO_INCREMENT NOT NULL, score INT NOT NULL, total_questions INT NOT NULL, started_at DATETIME NOT NULL, completed_at DATETIME DEFAULT NULL, user_id INT NOT NULL, quiz_id INT NOT NULL, INDEX IDX_AB6AFC6A76ED395 (user_id), INDEX IDX_AB6AFC6853CD175 (quiz_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_answer (id INT AUTO_INCREMENT NOT NULL, attempt_id INT NOT NULL, question_id INT NOT NULL, selected_answer_id INT NOT NULL, INDEX IDX_BF8F5118B191BE6B (attempt_id), INDEX IDX_BF8F51181E27F6BF (question_id), INDEX IDX_BF8F5118F24C5BEC (selected_answer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE quiz_attempt ADD CONSTRAINT FK_AB6AFC6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_attempt ADD CONSTRAINT FK_AB6AFC6853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F5118B191BE6B FOREIGN KEY (attempt_id) REFERENCES quiz_attempt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F51181E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F5118F24C5BEC FOREIGN KEY (selected_answer_id) REFERENCES answer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_attempt DROP FOREIGN KEY FK_AB6AFC6A76ED395');
        $this->addSql('ALTER TABLE quiz_attempt DROP FOREIGN KEY FK_AB6AFC6853CD175');
        $this->addSql('ALTER TABLE user_answer DROP FOREIGN KEY FK_BF8F5118B191BE6B');
        $this->addSql('ALTER TABLE user_answer DROP FOREIGN KEY FK_BF8F51181E27F6BF');
        $this->addSql('ALTER TABLE user_answer DROP FOREIGN KEY FK_BF8F5118F24C5BEC');
        $this->addSql('DROP TABLE quiz_attempt');
        $this->addSql('DROP TABLE user_answer');
    }
}
