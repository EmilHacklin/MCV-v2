<?php

namespace App\Cards;

/**
 * Card
 */
class Card
{
    /**
     * Constant strings for the no rank anc no suit message
     */
    public const NO_RANK = "no rank";
    public const NO_SUIT = "no suit";

    protected string $rank;
    protected string $suit;
    protected string $color;
    protected int $value;

    /**
     * __construct
     *
     * Constructor of the class
     *
     * @param  string $rank
     * @param  string $suit
     * @return void
     */
    public function __construct(string $rank, string $suit)
    {
        $this->findRank($rank);

        $this->findSuit($suit);

        $this->findValue();
    }

    /**
     * findRank
     *
     * Finds rank that matches the string
     *
     * @param  string $rank
     * @return void
     */
    protected function findRank(string $rank): void
    {
        match ($rank) {
            'a', 'A', "ace", "Ace" => $this->rank = 'A',
            '2', "two", "Two" => $this->rank = '2',
            '3', "three", "Three" => $this->rank = '3',
            '4', "four", "Four" => $this->rank = '4',
            '5', "five", "Five" => $this->rank = '5',
            '6', "six", "Six" => $this->rank = '6',
            '7', "seven", "Seven" => $this->rank = '7',
            '8', "eight", "Eight" => $this->rank = '8',
            '9', "nine", "Nine" => $this->rank = '9',
            '10', "ten", "Ten" => $this->rank = '10',
            '11', 'j', 'J', "jack", "Jack" => $this->rank = 'J',
            'c', 'C', "knave", "Knave", "knight", "Knight" => $this->rank = 'C',
            '12', 'q', 'Q', "lady", "Lady", "queen", "Queen" => $this->rank = 'Q',
            '13', 'k', 'K', "king", "King" => $this->rank = 'K',
            "joker", "Joker" => $this->rank = "Joker",
            default => $this->rank = self::NO_RANK,
        };
    }

    /**
     * findSuit
     *
     * Finds suit that matches the string
     *
     * @param  string $suit
     * @return void
     */
    protected function findSuit(string $suit): void
    {
        match ($suit) {
            '♥', '♡', "hearts", "Hearts", "heart", "Heart" => $this->suit = '♥',
            '♦', '♢', "tiles", "Tiles","tile", "Tile", "diamonds", "Diamonds", "diamond", "Diamond" => $this->suit = '♦',
            '♣', '♧', "clovers", "Clovers", "clover", "Clover", "clubs", "Clubs", "club", "Club" => $this->suit = '♣',
            '♠', '♤', "pikes", "Pikes", "pike", "Pike", "spades", "Spades" ,"spade", "Spade" => $this->suit = '♠',
            "red", "Red" => $this->suit = "Red",
            "black", "Black" => $this->suit = "Black",
            "white", "White" => $this->suit = "White",
            default => $this->suit = self::NO_SUIT,
        };

        $this->checkIfSuitValid();
    }

    /**
     * findValue
     *
     * Finds value of the rank
     *
     * @return void
     */
    protected function findValue(): void
    {
        if ($this->suit != self::NO_SUIT) {
            match ($this->rank) {
                'a', 'A', "ace", "Ace" => $this->value = 1,
                '2', "two", "Two" => $this->value = 2,
                '3', "three", "Three" => $this->value = 3,
                '4', "four", "Four" => $this->value = 4,
                '5', "five", "Five" => $this->value = 5,
                '6', "six", "Six" => $this->value = 6,
                '7', "seven", "Seven" => $this->value = 7,
                '8', "eight", "Eight" => $this->value = 8,
                '9', "nine", "Nine" => $this->value = 9,
                '10', "ten", "Ten" => $this->value = 10,
                '11', 'j', 'J', "jack", "Jack" => $this->value = 11,
                'c', 'C', "knave", "Knave", "knight", "Knight" => $this->value = 11,
                '12', 'q', 'Q', "lady", "Lady", "queen", "Queen" => $this->value = 12,
                '13', 'k', 'K', "king", "King" => $this->value = 13,
                default => $this->value = 0,
            };
        }
    }

    /**
     * checkIfSuitValid
     *
     * Checks if the Joker suits 'Red', 'Black' and 'White' is only assigned to a card with rank 'Joker'
     *
     * @return void
     */
    protected function checkIfSuitValid(): void
    {
        match ($this->rank) {
            "Joker" =>
            match ($this->suit) {
                "red", "Red", "black", "Black", "white", "White" => $this->suit,
                default => $this->suit = self::NO_SUIT,
            },
            default =>
            match ($this->suit) {
                "red", "Red", "black", "Black", "white", "White" => $this->suit = self::NO_SUIT,
                default => $this->suit,
            },
        };

        if ($this->suit == self::NO_SUIT) {
            $this->value = 0;
        }
    }

    /**
     * getRank
     *
     * Returns the rank of the card
     *
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * setRank
     *
     * Sets the rank of the card
     *
     * @param  string $rank
     * @return void
     */
    public function setRank(string $rank): void
    {
        $this->findRank($rank);

        $this->checkIfSuitValid();

        $this->findValue();
    }

    /**
     * getSuit
     *
     * Returns the suit of the cards
     *
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * setSuit
     *
     * Sets the suit of the cards
     *
     * @param  string $suit
     * @return void
     */
    public function setSuit(string $suit): void
    {
        $this->findSuit($suit);

        $this->findValue();
    }

    /**
     * getValue
     *
     * Returns the value of the card
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * getString
     *
     * Returns the rank and suit of the card as a string
     *
     * @return string
     */
    public function getString(): string
    {
        return "[". $this->suit . " " . $this->rank . "]";
    }
}
