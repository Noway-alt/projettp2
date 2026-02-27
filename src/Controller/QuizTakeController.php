<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizAttempt;
use App\Entity\UserAnswer;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/take-quiz')]
#[IsGranted('ROLE_USER')]
class QuizTakeController extends AbstractController
{
    // Liste des quiz disponibles
    #[Route('/', name: 'app_quiz_take_index')]
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('quiz_take/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }

    // Démarrer un quiz
    #[Route('/start/{id}', name: 'app_quiz_take_start')]
    public function start(Quiz $quiz, EntityManagerInterface $em): Response
    {
        // Créer une nouvelle tentative
        $attempt = new QuizAttempt();
        $attempt->setUser($this->getUser());
        $attempt->setQuiz($quiz);
        $attempt->setStartedAt(new \DateTime());
        $attempt->setTotalQuestions($quiz->getQuestions()->count());
        $attempt->setScore(0);

        $em->persist($attempt);
        $em->flush();

        return $this->redirectToRoute('app_quiz_take_question', [
            'attemptId' => $attempt->getId(),
            'questionIndex' => 0,
        ]);
    }

    // Afficher une question
    #[Route('/attempt/{attemptId}/question/{questionIndex}', name: 'app_quiz_take_question')]
    public function question(int $attemptId, int $questionIndex, EntityManagerInterface $em): Response
    {
        $attempt = $em->getRepository(QuizAttempt::class)->find($attemptId);
        
        if (!$attempt || $attempt->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $questions = $attempt->getQuiz()->getQuestions()->toArray();
        
        // Si toutes les questions sont répondues
        if ($questionIndex >= count($questions)) {
            return $this->redirectToRoute('app_quiz_take_results', ['attemptId' => $attemptId]);
        }

        $currentQuestion = $questions[$questionIndex];

        return $this->render('quiz_take/question.html.twig', [
            'attempt' => $attempt,
            'question' => $currentQuestion,
            'questionIndex' => $questionIndex,
            'totalQuestions' => count($questions),
        ]);
    }

    // Soumettre une réponse
    #[Route('/attempt/{attemptId}/answer', name: 'app_quiz_take_answer', methods: ['POST'])]
    public function answer(Request $request, int $attemptId, EntityManagerInterface $em): Response
    {
        $attempt = $em->getRepository(QuizAttempt::class)->find($attemptId);
        
        if (!$attempt || $attempt->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $questionId = $request->request->get('question_id');
        $answerId = $request->request->get('answer_id');
        $questionIndex = $request->request->get('question_index');

        $question = $em->getRepository(\App\Entity\Question::class)->find($questionId);
        $answer = $em->getRepository(\App\Entity\Answer::class)->find($answerId);

        // Enregistrer la réponse
        $userAnswer = new UserAnswer();
        $userAnswer->setAttempt($attempt);
        $userAnswer->setQuestion($question);
        $userAnswer->setSelectedAnswer($answer);

        // Si la réponse est correcte, incrémenter le score
        if ($answer->isCorrect()) {
            $attempt->setScore($attempt->getScore() + 1);
        }

        $em->persist($userAnswer);
        $em->flush();

        // Question suivante
        return $this->redirectToRoute('app_quiz_take_question', [
            'attemptId' => $attemptId,
            'questionIndex' => $questionIndex + 1,
        ]);
    }

    // Afficher les résultats
    #[Route('/attempt/{attemptId}/results', name: 'app_quiz_take_results')]
    public function results(int $attemptId, EntityManagerInterface $em): Response
    {
        $attempt = $em->getRepository(QuizAttempt::class)->find($attemptId);
        
        if (!$attempt || $attempt->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        // Marquer comme complété
        if (!$attempt->getCompletedAt()) {
            $attempt->setCompletedAt(new \DateTime());
            $em->flush();
        }

        $percentage = ($attempt->getScore() / $attempt->getTotalQuestions()) * 100;

        return $this->render('quiz_take/results.html.twig', [
            'attempt' => $attempt,
            'percentage' => $percentage,
        ]);
    }
}

