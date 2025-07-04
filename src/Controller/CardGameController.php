<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Cards\CardHand;
use App\Cards\DeckOfCards;

class CardGameController extends AbstractController
{
    #[Route("/game/card", name: "card_start")]
    public function cardHome(
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();

        $session->set("cards_deck", $deck);

        return $this->render('card/home.html.twig');
    }

    #[Route("/game/card/session", name: "card_session", methods: ['GET'])]
    public function session(
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();

        $info = $deck->getString();

        $data = [
            "deck" => $info,
        ];

        return $this->render('card/session.html.twig', $data);
    }

    #[Route("/game/card/session/delete", name: "card_session_delete", methods: ['GET'])]
    public function deleteSession(
        Request $request,
    ): Response {
        $request->getSession()->invalidate(1);

        $data = [
            "deck" => "",
        ];

        $this->addFlash(
            'notice',
            'Your session data has been deleted!'
        );

        return $this->render('card/session.html.twig', $data);
    }

    #[Route("/game/card/deck", name: "card_deck")]
    public function deck(
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();

        $deck->resetDeck();

        $session->set("cards_deck", $deck);

        $data = [

            "deck" => $deck->getString(),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/game/card/deck/shuffle", name: "card_deck_shuffle")]
    public function deckShuffle(
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();

        $deck->reshuffleDeck();

        $session->set("cards_deck", $deck);

        $data = [
            "deck" => $deck->getString(),
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/game/card/deck/draw", name: "card_deck_draw")]
    public function deckDraw(
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();
        $hand = new CardHand();

        ($deck->numberOfCards() > 0) ?
        $hand->addCard($deck->drawCard()) :
        throw new Exception("Can not draw more cards as the deck is empty!");

        $session->set("cards_deck", $deck);

        $data = [
            "hand" => $hand->getString(),
            "deckNumber" => $deck->numberOfCards(),
        ];

        return $this->render('card/deck_draw.html.twig', $data);
    }

    #[Route("/game/card/deck/draw/{num<\d+>}", name: "card_deck_draw_many")]
    public function deckDrawMany(
        int $num,
        SessionInterface $session
    ): Response {
        if ($num > 52) {
            throw new Exception("Can not draw more than cards in deck!");
        }
        if ($num < 1) {
            throw new Exception("Can not draw less than 1 card!");
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();
        $hand = new CardHand();

        $numberOfCards = $deck->numberOfCards();

        if ($numberOfCards == 0) {
            throw new Exception("Can not draw more cards as the deck is empty!");
        }
        if ($numberOfCards < $num) {
            throw new Exception("Can not draw more cards as the deck currently have!\n
            The deck currently have ". $numberOfCards . " many cards in the deck.");
        }

        for ($i = 0; $i < $num; $i++) {
            $hand->addCard($deck->drawCard());
        }

        $session->set("cards_deck", $deck);

        $data = [
            "hand" => $hand->getString(),
            "deckNumber" => $deck->numberOfCards(),
        ];

        return $this->render('card/deck_draw.html.twig', $data);
    }
}
