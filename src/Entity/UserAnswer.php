<?php

namespace App\Entity;

use App\Repository\UserAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAnswerRepository::class)]
class UserAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: QuizAttempt::class, inversedBy: 'userAnswers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?QuizAttempt $attempt = null;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Question $question = null;

    #[ORM\ManyToOne(targetEntity: Answer::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Answer $selectedAnswer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttempt(): ?QuizAttempt
    {
        return $this->attempt;
    }

    public function setAttempt(?QuizAttempt $attempt): static
    {
        $this->attempt = $attempt;
        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;
        return $this;
    }

    public function getSelectedAnswer(): ?Answer
    {
        return $this->selectedAnswer;
    }

    public function setSelectedAnswer(?Answer $selectedAnswer): static
    {
        $this->selectedAnswer = $selectedAnswer;
        return $this;
    }
}