<?php

namespace App\Cards;

use App\Cards\CardGraphic;

/**
 * DeckOfCards
 */
class DeckOfCards
{
    public const RANKS = array("A", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "J", "Q", "K");
    public const SUITES = array("Hearts", "Diamonds", "Clubs", "Spades");

    /**
    * @var array<CardGraphic> $deck
    */
    private array $deck = [];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->createDeck();
    }

    /**
     * createDeck
     *
     * @return void
     */
    private function createDeck(): void
    {
        foreach (self::SUITES as $suite) {
            foreach (self::RANKS as $rank) {
                $this->deck[] = new CardGraphic($rank, $suite);
            }
        }
    }

    /**
     * resetDeck
     *
     * @return void
     */
    public function resetDeck(): void
    {
        $this->deck = [];

        $this->createDeck();
    }

    /**
     * shuffleDeck
     *
     * @return void
     */
    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    /**
     * reshuffleDeck
     *
     * @return void
     */
    public function reshuffleDeck(): void
    {
        $this->deck = [];

        $this->createDeck();

        $this->shuffleDeck();
    }

    /**
     * drawCard
     *
     * @return CardGraphic
     */
    public function drawCard(): CardGraphic
    {
        $card = array_shift($this->deck);

        if ($card == null) {
            $card = new CardGraphic("no rank", "no suite");
        }

        return $card;
    }

    /**
     * numberOfCards
     *
     * @return int
     */
    public function numberOfCards(): int
    {
        return count($this->deck);
    }

    /**
     * getString
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
