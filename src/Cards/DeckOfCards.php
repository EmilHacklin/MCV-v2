<?php

namespace App\Cards;

use App\Cards\CardGraphic;

class DeckOfCards
{
    public const RANKS = array("A", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "J", "Q", "K");
    public const SUITES = array("Hearts", "Diamonds", "Clubs", "Spades");

    private $deck = [];

    public function __construct()
    {
        $this->createDeck();
    }

    private function createDeck()
    {
        foreach (self::SUITES as $suite) {
            foreach (self::RANKS as $rank) {
                $this->deck[] = new CardGraphic($rank, $suite);
            }
        }
    }

    public function resetDeck()
    {
        $this->deck = [];

        $this->createDeck();
    }

    public function shuffleDeck()
    {
        shuffle($this->deck);
    }

    public function reshuffleDeck()
    {
        $this->deck = [];

        $this->createDeck();

        $this->shuffleDeck();
    }

    public function drawCard(): CardGraphic
    {
        if ($this->numberOfCards() > 0) {
            return array_shift($this->deck);
        } else {
            return new CardGraphic("", "");
        }
    }

    public function numberOfCards(): int
    {
        return count($this->deck);
    }

    public function getString(): array
    {
        $cards = [];
        foreach ($this->deck as $card) {
            $cards[] = $card->getString();
        }
        return $cards;
    }
}
