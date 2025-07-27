<?php

namespace App\Cards;

/**
 * DeckOfCards.
 *
 * Class that generates a deck of cards and it's functions
 */
class DeckOfCards
{
    /**
     * Constant arrays that contain all ranks and suites in the deck.
     */
    public const RANKS = ['A', '2', '3', '4', '5', '6', '7', '8', '8', '10', 'J', 'Q', 'K'];
    public const SUITES = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];

    /**
     * @var array<CardGraphic>
     *                         Is an array that contains the CardGraphic objects
     */
    private array $deck = [];

    /**
     * __construct.
     *
     * Constructor of the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->createDeck();
    }

    /**
     * createDeck.
     *
     * Creates a deck containing all card combinations of ranks and suites
     */
    private function createDeck(): void
    {
        foreach (self::SUITES as $suit) {
            foreach (self::RANKS as $rank) {
                $this->deck[] = new CardGraphic($rank, $suit);
            }
        }
    }

    /**
     * resetDeck.
     *
     * Resets the deck to the original state
     */
    public function resetDeck(): void
    {
        $this->deck = [];

        $this->createDeck();
    }

    /**
     * shuffleDeck.
     *
     * Shuffles the cards in the deck around
     */
    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    /**
     * reshuffleDeck.
     *
     * Resets the deck and then shuffles it again
     */
    public function reshuffleDeck(): void
    {
        $this->deck = [];

        $this->createDeck();

        $this->shuffleDeck();
    }

    /**
     * drawCard.
     *
     * Draws a card from the deck an returns it
     */
    public function drawCard(): CardGraphic
    {
        $card = array_shift($this->deck);

        if (null == $card) {
            $card = new CardGraphic(CardGraphic::NO_RANK, CardGraphic::NO_SUIT);
        }

        return $card;
    }

    /**
     * cardCount.
     *
     * Returns the number of cards left in the deck
     */
    public function cardCount(): int
    {
        return count($this->deck);
    }

    /**
     * getString.
     *
     * Returns all the cards in the deck as strings array
     *
     * @return array<string>
     */
    public function getString(): array
    {
        $cards = [];
        foreach ($this->deck as $card) {
            $cards[] = $card->getString();
        }

        return $cards;
    }
}
