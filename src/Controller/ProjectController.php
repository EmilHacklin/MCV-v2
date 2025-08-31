<?php

namespace App\Controller;

use App\Game\BlackJack;
use App\Game\BlackJack\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/proj', name: 'proj_start')]
    public function home(): Response
    {
        return $this->render('proj/home.html.twig');
    }

    #[Route('/proj/about', name: 'proj_doc')]
    public function about(): Response
    {
        return $this->render('proj/doc.html.twig');
    }

    #[Route('/proj/player/init/{num<\d+>}', name: 'proj_player_init')]
    public function playerInit(
        int $num,
    ): Response {
        if ($num > BlackJack::MAX_PLAYERS) {
            throw new \RuntimeException("Can't have more then ".BlackJack::MAX_PLAYERS.'players!');
        }
        if ($num < 1) {
            throw new \RuntimeException("Can't have less than 1 player!");
        }

        $data = [
            'numOfPlayers' => $num,
            'players' => [],
        ];

        // Create default name
        for ($i = 1; $i <= $num; ++$i) {
            $data['players'][] = "Player $i";
        }

        return $this->render('proj/player_creation.html.twig', $data);
    }

    #[Route('/proj/blackjack/init', name: 'proj_BlackJack_init', methods: ['POST'])]
    public function blackJackInit(
        SessionInterface $session,
        Request $request,
    ): Response {
        /** @var BlackJack $blackJack */
        $blackJack = new BlackJack();

        /** @var int $numOfPlayers */
        $numOfPlayers = $request->request->get('numOfPlayers');

        // Get the names of the players
        $players = [];

        for ($i = 1; $i <= $numOfPlayers; ++$i) {
            /** @var string|null $playerName */
            $playerName = $request->request->get("Player_$i");
            if (null === $playerName || '' === trim($playerName)) {
                $playerName = "Player $i"; // default name if blank
            }
            $players[] = new Player($playerName);
        }

        $blackJack->setPlayers($players);

        // Save to session
        $session->set('proj_black_jack', $blackJack);
        $session->set('proj_newGame', true);
        $session->set('proj_playerTurn', 0);

        return $this->redirectToRoute('proj_BlackJack_game');
    }

    #[Route('/proj/blackjack/game', name: 'proj_BlackJack_game', methods: ['GET'])]
    public function blackJack(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack|null $blackJack */
        $blackJack = $session->get('proj_black_jack');
        /** @var bool|null $newGame */
        $newGame = $session->get('proj_newGame');
        /** @var int|null $playerTurn */
        $playerTurn = $session->get('proj_playerTurn');

        // redirect if blackJack or newGame or playerTurn is not in session
        if (null === $blackJack || null === $newGame || null === $playerTurn) {
            $this->addFlash(
                'warning',
                'A Black Jack game is not setup, choose a number of players to start!'
            );

            return $this->redirectToRoute('proj_start');
        }

        $data = $blackJack->stateOfGame();

        // If it is any of the players turn
        if ($playerTurn < $data['numOfPlayers']) {
            // If the player is broke skip them
            if (true === $blackJack->isPlayerBroke($playerTurn)) {
                ++$playerTurn;
                $session->set('proj_playerTurn', $playerTurn);

                return $this->redirectToRoute('proj_BlackJack_game');
            }
        }

        // Add data
        $data['newGame'] = $newGame;
        $data['playerTurn'] = $playerTurn;

        return $this->render('proj/black_jack.html.twig', $data);
    }

    #[Route('/proj/blackjack/bet', name: 'proj_BlackJack_Bet', methods: ['POST'])]
    public function blackJacBet(
        SessionInterface $session,
        Request $request,
    ): Response {
        /** @var BlackJack|null $blackJack */
        $blackJack = $session->get('proj_black_jack');
        /** @var bool|null $newGame */
        $newGame = $session->get('proj_newGame');

        // redirect if blackJack or newGame is not in session
        if (null === $blackJack || null === $newGame) {
            $this->addFlash(
                'warning',
                'A Black Jack game is not setup, choose a number of players to start!'
            );

            return $this->redirectToRoute('proj_start');
        }

        /** @var int $numOfPlayers */
        $numOfPlayers = $request->request->get('numOfPlayers');

        $bets = [];

        for ($i = 0; $i < $numOfPlayers; ++$i) {
            /** @var int|null $bet */
            $bet = $request->request->get("$i");
            if (null !== $bet) {
                $bets[$i] = $bet;
            }
        }

        $blackJack->newGame($bets);

        // Save to session
        $session->set('proj_black_jack', $blackJack);
        $session->set('proj_newGame', false);

        return $this->redirectToRoute('proj_BlackJack_game');
    }

    #[Route('/proj/blackjack/stay', name: 'proj_BlackJack_Stay', methods: ['POST'])]
    public function blackJackStay(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack|null $blackJack */
        $blackJack = $session->get('proj_black_jack');
        /** @var int|null $playerTurn */
        $playerTurn = $session->get('proj_playerTurn');

        // redirect if blackJack or playerTurn is not in session
        if (null === $blackJack || null === $playerTurn) {
            $this->addFlash(
                'warning',
                'A Black Jack game is not setup, choose a number of players to start!'
            );

            return $this->redirectToRoute('proj_start');
        }

        $blackJack->stayPlayer($playerTurn);
        ++$playerTurn;

        // Save to session
        $session->set('black_jack', $blackJack);
        $session->set('proj_playerTurn', $playerTurn);

        return $this->redirectToRoute('proj_BlackJack_game');
    }

    #[Route('/proj/blackjack/hit', name: 'proj_BlackJack_Hit', methods: ['POST'])]
    public function blackJackHit(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack|null $blackJack */
        $blackJack = $session->get('proj_black_jack');
        /** @var int|null $playerTurn */
        $playerTurn = $session->get('proj_playerTurn');

        // redirect if blackJack or playerTurn is not in session
        if (null === $blackJack || null === $playerTurn) {
            $this->addFlash(
                'warning',
                'A Black Jack game is not setup, choose a number of players to start!'
            );

            return $this->redirectToRoute('proj_BlackJack_game');
        }

        $blackJack->hitPlayer($playerTurn);

        // If bust then next players turn
        if (true === $blackJack->isPlayerBust($playerTurn)) {
            ++$playerTurn;
            $session->set('proj_playerTurn', $playerTurn);
        }

        // Save to session
        $session->set('black_jack', $blackJack);

        return $this->redirectToRoute('proj_BlackJack_game');
    }

    #[Route('/proj/blackjack/double_down', name: 'proj_BlackJack_DoubleDown', methods: ['POST'])]
    public function blackJackDoubleDown(
        SessionInterface $session,
    ): Response {
        /** @var BlackJack|null $blackJack */
        $blackJack = $session->get('proj_black_jack');
        /** @var int|null $playerTurn */
        $playerTurn = $session->get('proj_playerTurn');

        // redirect if blackJack or playerTurn is not in session
        if (null === $blackJack || null === $playerTurn) {
            $this->addFlash(
                'warning',
                'A Black Jack game is not setup, choose a number of players to start!'
            );

            return $this->redirectToRoute('proj_start');
        }

        $blackJack->doubleDownPlayer($playerTurn);
        ++$playerTurn;

        // Save to session
        $session->set('black_jack', $blackJack);
        $session->set('proj_playerTurn', $playerTurn);

        return $this->redirectToRoute('proj_BlackJack_game');
    }

    #[Route('/proj/blackjack/newGame', name: 'proj_BlackJack_NewGame', methods: ['POST'])]
    public function blackJackNewGame(
        SessionInterface $session,
    ): Response {
        // Save to session
        $session->set('proj_newGame', true);
        $session->set('proj_playerTurn', 0);

        return $this->redirectToRoute('proj_BlackJack_game');
    }
}
