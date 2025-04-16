<?php

namespace App\Controller;

use App\Entity\Scores;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/jouer', name: 'app_game_play')]
    public function play(QuestionRepository $questionRepository, Request $request): Response
    {
        $session = $request->getSession();
        $playedQuestions = $session->get('played_questions', []);

        // Réinitialiser le score si c’est le début
        if (empty($playedQuestions)) {
            $session->set('score', 0);
        }

        // Trouver une question non jouée
        $question = $questionRepository->findUnplayedQuestion($playedQuestions);

        // Si toutes les questions ont été jouées, fin de partie
        if (!$question) {
            return $this->redirectToRoute('app_game_end');
        }

        // Ajouter la question jouée à la session
        $playedQuestions[] = $question->getId();
        $session->set('played_questions', $playedQuestions);

        $currentScore = $session->get('score', 0);

        return $this->render('game/play.html.twig', [
            'question' => $question,
            'score' => $currentScore,
        ]);
    }

    #[Route('/check-answer', name: 'app_game_check', methods: ['POST'])]
    public function checkAnswer(Request $request, QuestionRepository $questionRepository): Response
    {
        $userAnswer = trim($request->request->get('user_answer', ''));
        $questionId = $request->request->get('question_id');

        if (empty($userAnswer)) {
            $this->addFlash('error', 'Veuillez entrer une réponse');
            return $this->redirectToRoute('app_game_play');
        }

        $question = $questionRepository->find($questionId);

        if (!$question) {
            $this->addFlash('error', 'Question introuvable');
            return $this->redirectToRoute('app_game_play');
        }

        $isCorrect = strtolower($userAnswer) === strtolower(trim($question->getReponse()));

        $session = $request->getSession();
        $score = $session->get('score', 0);

        if ($isCorrect) {
            $session->set('score', $score + 1);
            $this->addFlash('success', '✅ Bravo ! Bonne réponse');
        } else {
            $this->addFlash('error', sprintf('❌ Presque ! La réponse était "%s"', $question->getReponse()));
        }

        return $this->redirectToRoute('app_game_play');
    }

    #[Route('/fin-partie', name: 'app_game_end')]
    public function endGame(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $currentScore = $session->get('score', 0);

        if ($currentScore > 0) {
            $score = new Scores();
            $score->setUser($this->getUser());
            $score->setValeur($currentScore);
            $score->setDatePartie(new \DateTime());

            $entityManager->persist($score);
            $entityManager->flush();

            $this->addFlash('success', '🎉 Partie terminée ! Votre score a été enregistré.');
        } else {
            $this->addFlash('error', 'Le score est nul, rien à enregistrer.');
        }

        // Nettoyer la session
        $session->remove('score');
        $session->remove('played_questions');

        return $this->redirectToRoute('app_score');
    }
}
