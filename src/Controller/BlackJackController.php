<?php

namespace App\Controller;

use App\Cards\BlackJack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BlackJackController extends AbstractController
{
    #[Route('/game', name: 'game_start')]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route('/game/blackJack', name: 'game_black_jack', methods: ['GET'])]
    public function blackJack(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack $blackJack */
        $blackJack = $session->get('black_jack') ?? new BlackJack();

        $session->set('black_jack', $blackJack);

        $data = $blackJack->stateOfGame();

        return $this->render('game/black_jack.html.twig', $data);
    }

    #[Route('/game/blackJack/hit', name: 'black_jack_hit', methods: ['POST'])]
    public function blackJackHit(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack $blackJack */
        $blackJack = $session->get('black_jack') ?? new BlackJack();

        $blackJack->hitPlayer();

        $session->set('black_jack', $blackJack);

        if ($blackJack->isPlayerBust()) {
            $this->addFlash(
                'warning',
                'You have gone bust!'
            );
        }

        return $this->redirectToRoute('game_black_jack');
    }

    #[Route('/game/blackJack/stay', name: 'black_jack_stay', methods: ['POST'])]
    public function blackJackStay(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack $blackJack */
        $blackJack = $session->get('black_jack') ?? new BlackJack();

        $blackJack->stayPlayer();

        $session->set('black_jack', $blackJack);

        return $this->redirectToRoute('game_black_jack');
    }

    #[Route('/game/blackJack/reset', name: 'black_jack_reset', methods: ['POST'])]
    public function blackJackReset(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack $blackJack */
        $blackJack = $session->get('black_jack') ?? new BlackJack();

        $blackJack->resetGame();

        $session->set('black_jack', $blackJack);

        return $this->redirectToRoute('game_black_jack');
    }
}
