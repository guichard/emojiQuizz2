<?php

namespace App\Controller;

use App\Repository\ScoresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ScoreController extends AbstractController
{
    #[Route('/score', name: 'app_score')]
    public function index(ScoresRepository $scoresRepository, Request $request): Response
    {
        // Récupérer les meilleurs scores
        $meilleursScores = $scoresRepository->findBy([], ['valeur' => 'DESC'], 10);

        // Récupérer le score actuel de la session
        $session = $request->getSession();
        $currentScore = $session->get('score', 0);

        // Renvoyer la réponse avec les scores et le score actuel
        return $this->render('score/index.html.twig', [
            'meilleurs_scores' => $meilleursScores,
            'currentScore' => $currentScore,
        ]);
    }
}

