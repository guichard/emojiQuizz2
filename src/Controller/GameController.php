<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GameController extends AbstractController
{
    #[Route('/jouer', name: 'app_game_play')]
    public function play(QuestionRepository $questionRepository, Request $request): Response
    {
        // Récupérer les questions déjà posées depuis la session
        $session = $request->getSession();
        $playedQuestions = $session->get('played_questions', []);
        
        // Trouver une question non jouée
        $question = $questionRepository->findUnplayedQuestion($playedQuestions);
        
        if (!$question) {
            // Réinitialiser si toutes les questions ont été jouées
            $playedQuestions = [];
            $session->set('played_questions', []);
            $question = $questionRepository->findRandomQuestion();
        }
        
        // Ajouter la question actuelle aux questions jouées
        $playedQuestions[] = $question->getId();
        $session->set('played_questions', $playedQuestions);
        
        return $this->render('game/play.html.twig', [
            'question' => $question,
        ]);
}
    
    #[Route('/check-answer', name: 'app_game_check', methods: ['POST'])]
    public function checkAnswer(Request $request, QuestionRepository $questionRepository): Response
    {
        // 1. Récupération des données du formulaire
        $userAnswer = trim($request->request->get('user_answer', ''));
        $questionId = $request->request->get('question_id');
        
        // 2. Validation des données
        if (empty($userAnswer)) {
            $this->addFlash('error', 'Veuillez entrer une réponse');
            return $this->redirectToRoute('app_game_play');
        }
        // 3. Récupération de la question
        $question = $questionRepository->find($questionId);
        
        if (!$question) {
            $this->addFlash('error', 'Question introuvable');
            return $this->redirectToRoute('app_game_play');
        }

        // 4. Vérification de la réponse (insensible à la casse)
        $isCorrect = strtolower($userAnswer) === strtolower(trim($question->getReponse()));

        // 5. Mise à jour du score en session
        $session = $request->getSession();
        $score = $session->get('score', 0);
        
        if ($isCorrect) {
            $session->set('score', $score + 1);
            $this->addFlash('success', '✅ Bravo ! Bonne réponse');
        } else {
            $this->addFlash('error', sprintf('❌ Presque ! La réponse était "%s"', $question->getReponse()));
        }

        // 6. Redirection vers la page de jeu
        return $this->redirectToRoute('app_game_play');
    }
}
$score = new Score();
$score->setUser($this->getUser());
$score->setValeur($session->get('score'));
$score->setDatePartie(new \DateTime());
$entityManager->persist($score);
$entityManager->flush();
