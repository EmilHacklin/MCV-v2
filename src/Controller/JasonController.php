<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Cards\CardHand;
use App\Cards\DeckOfCards;
use App\Cards\BlackJack;
use App\Repository\BookRepository;

class JasonController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('api.html.twig');
    }

    #[Route("/api/quote", name: "api/quote", methods: ['GET'])]
    public function quote(): Response
    {
        $quotes = array(
            array(
                "quote" => "The cleaner and nicer the program, the faster it's going to run. And if it doesn't, it'll be easy to make it fast.",
                "author" => "Joshua Bloch, in an interview by Peter Seibel in Coders At Work book"
            ),
            array(
                "quote" => "Playing with pointers is like playing with fire. Fire is perhaps the most important tool known to man. Carefully used, fire brings enormous benefits; but when fire gets out of control, disaster strikes.",
                "author" => "John Barnes, Programming in Ada 2012, Cambridge University Press, 2014, p. 189"
            ),
            array(
                "quote" => "Applications programming is a race between software engineers, who strive to produce idiot-proof programs, and the universe which strives to produce bigger idiots. So far the Universe is winning.",
                "author" => "Rick Cook, The Wizardry Compiled (1989) Ch. 6"
            ),
            array(
                "quote" => "Computers are man's attempt at designing a cat: it does whatever it wants, whenever it wants, and rarely ever at the right time.",
                "author" => "EMCIC, Keenspot Elf Life Forum, 2001-Apr-26"
            ),
            array(
                "quote" => "There is no programming language, no matter how structured, that will prevent programmers from making bad programs.",
                "author" => "Larry Flon (1975) 'On research in structured programming'. SIGPLAN Not., 10(10), pp.16–17"
            ),
            array(
                "quote" => "The main activity of programming is not the origination of new independent programs, but in the integration, modification, and explanation of existing ones.",
                "author" => "Terry Winograd (1991) 'Beyond Programming Languages', in Artificial intelligence & software engineering, ed. Derek Partridge, p. 317"
            )
        );

        $randInt = random_int(0, count($quotes) - 1);

        $data = [
            'quote' => $quotes[$randInt]["quote"],
            'author' => $quotes[$randInt]["author"],
            'timestamp' => date("d/m-y H:i:s", time())
        ];

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck", name: "api/deck", methods: ['GET'])]
    public function apiDeck(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "deck" => $deck->getString(),
        ];

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api/deck/shuffle", methods: ['POST'])]
    public function apiDeckShuffle(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();

        $deck->shuffleDeck();

        $session->set("cards_deck", $deck);

        $data = [
            "deck" => $deck->getString(),
        ];

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw", name: "api/deck/draw", methods: ['POST'])]
    public function apiDeckDraw(
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();
        $hand = new CardHand();

        ($deck->cardCount() > 0) ?
        $hand->addCard($deck->drawCard()) :
        throw new Exception("Can't draw more cards as the deck is empty!");

        $session->set("cards_deck", $deck);

        $data = [
            "hand" => $hand->getString(),
            "deckCount" => $deck->cardCount(),
        ];

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "api/deck/draw/:number", methods: ['POST'])]
    public function apiDeckDrawMany(
        int $num,
        SessionInterface $session
    ): Response {
        if ($num > 52) {
            throw new Exception("Can't draw more than cards in deck!");
        }
        if ($num < 1) {
            throw new Exception("Can't draw less than 1 card!");
        }

        /** @var DeckOfCards $deck */
        $deck = $session->get("cards_deck") ?? new DeckOfCards();
        $hand = new CardHand();

        $cardCount = $deck->cardCount();
        if ($cardCount == 0) {
            throw new Exception("Can't draw more cards as the deck is empty!");
        }
        if ($cardCount < $num) {
            throw new Exception("Can't draw more cards as the deck currently have!\n
            The deck currently have ". $cardCount . " many cards in the deck.");
        }

        for ($i = 0; $i < $num; $i++) {
            $hand->addCard($deck->drawCard());
        }

        $session->set("cards_deck", $deck);

        $data = [
            "hand" => $hand->getString(),
            "deckCount" => $deck->cardCount(),
        ];

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/game", name: "api/game", methods: ['GET'])]
    public function apiGame(
        SessionInterface $session
    ): Response {
        /** @var BlackJack $blackJack */
        $blackJack = $session->get("black_jack") ?? new BlackJack();

        $data = $blackJack->stateOfGame();
        ;

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("api/library/books", name: "api/library/books", methods: ['GET'])]
    public function apiLibraryBooks(
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository->readAllBooks();

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("api/library/book/{isbn<\d+>}", name: "api/library/book/isbn", methods: ['GET'])]
    public function apiLibraryBookISBN(
        string $isbn,
        BookRepository $bookRepository
    ): Response {
        $data = $bookRepository->readOneBookISBN($isbn);

        $response = new JsonResponse($data);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
