<?php

namespace App\Cards;

/**
 * Card
 */
class Card
{
    protected string $rank;
    protected string $suite;
    protected string $color;
    protected int $value;

    /**
     * __construct
     *
     * @param  string $rank
     * @param  string $suite
     * @return void
     */
    public function __construct(string $rank, string $suite)
    {
        $this->findRank($rank);

        $this->setSuite($suite);

        $this->findValue();
    }

    /**
     * findRank
     *
     * @param  string $rank
     * @return void
     */
    protected function findRank(string $rank): void
    {
        match ($rank) {
            "a", "A", "ace", "Ace" => $this->rank = "A",
            "2", "two", "Two" => $this->rank = "2",
            "3", "three", "Three" => $this->rank = "3",
            "4", "four", "Four" => $this->rank = "4",
            "5", "five", "Five" => $this->rank = "5",
            "6", "six", "Six" => $this->rank = "6",
            "7", "seven", "Seven" => $this->rank = "7",
            "8", "eight", "Eight" => $this->rank = "8",
            "9", "nine", "Nine" => $this->rank = "9",
            "10", "ten", "Ten" => $this->rank = "10",
            "11", "j", "J", "jack", "Jack" => $this->rank = "J",
            "c", "C", "knave", "Knave", "knight", "Knight" => $this->rank = "C",
            "12", "q", "Q", "lady", "Lady", "queen", "Queen" => $this->rank = "Q",
            "13", "k", "K", "king", "King" => $this->rank = "K",
            default => $this->rank = "no rank",
        };
    }

    /**
     * findValue
     *
     * @return void
     */
    protected function findValue(): void
    {
        match ($this->rank) {
            "a", "A", "ace", "Ace" => $this->value = 1,
            "2", "two", "Two" => $this->value = 2,
            "3", "three", "Three" => $this->value = 3,
            "4", "four", "Four" => $this->value = 4,
            "5", "five", "Five" => $this->value = 5,
            "6", "six", "Six" => $this->value = 6,
            "7", "seven", "Seven" => $this->value = 7,
            "8", "eight", "Eight" => $this->value = 8,
            "9", "nine", "Nine" => $this->value = 9,
            "10", "ten", "Ten" => $this->value = 10,
            "11", "j", "J","jack", "Jack", "c", "C", "knave", "Knave", "knight", "Knight" => $this->value = 11,
            "12", "q", "Q", "lady", "Lady", "queen", "Queen" => $this->value = 12,
            "13", "k", "K", "king", "King" => $this->value = 13,
            default => $this->value = 0,
        };
    }

    /**
     * getRank
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
     * @param  string $rank
     * @return void
     */
    public function setRank(string $rank): void
    {
        $this->findRank($rank);

        $this->findValue();
    }

    /**
     * getSuit
     *
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suite;
    }

    /**
     * setSuite
     *
     * @param  string $suite
     * @return void
     */
    public function setSuite(string $suite): void
    {
        match ($suite) {
            "♥", "♡", "hearts", "Hearts" => $this->suite = "♥",
            "♦", "♢", "tiles", "Tiles", "diamonds", "Diamonds" => $this->suite = "♦",
            "♣", "♧", "clovers", "Clovers", "clubs", "Clubs" => $this->suite = "♣",
            "♠", "♤", "pikes", "Pikes", "spades", "Spades" => $this->suite = "♠",
            default => $this->suite = "no suite",
        };
    }

    /**
     * getValue
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
     * @return string
     */
    public function getString(): string
    {
        return "[". $this->suite . $this->rank . "]";
    }
}
