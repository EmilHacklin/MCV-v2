<?php

namespace App\Cards;

class Card
{
    protected $rank;
    protected $suite;
    protected $color;
    protected $value;

    public function __construct($rank, $suite)
    {
        $this->rank = $rank;

        $this->setSuit($suite);

        $this->findValue();
    }

    protected function findValue()
    {
        match ($this->rank) {
            "a", "A", "ace", "Ace" => $this->value = 1,
            2, "two", "Two" => $this->value = 2,
            3, "three", "Three" => $this->value = 3,
            4, "four", "Four" => $this->value = 4,
            5, "five", "Five" => $this->value = 5,
            6, "six", "Six" => $this->value = 6,
            7, "seven", "Seven" => $this->value = 7,
            8, "eight", "Eight" => $this->value = 8,
            9, "nine", "Nine" => $this->value = 9,
            10, "ten", "Ten" => $this->value = 10,
            11, "j", "J", "c", "C", "knave", "Knave", "jack", "Jack" => $this->value = 11,
            12, "q", "Q", "lady", "Lady", "queen", "Queen" => $this->value = 12,
            13, "k", "K", "king", "King" => $this->value = 13,
            default => $this->value = 0,
        };
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;

        $this->findValue();
    }

    public function getSuit(): string
    {
        $this->value = random_int(1, 6);
        return $this->value;
    }

    public function setSuit($suite)
    {
        match ($suite) {
            "♥", "♡", "hearts", "Hearts" => $this->suite = "♥",
            "♦", "♢", "tiles", "Tiles", "diamonds", "Diamonds" => $this->suite = "♦",
            "♣", "♧", "clovers", "Clovers", "clubs", "Clubs" => $this->suite = "♣",
            "♠", "♤", "pikes", "Pikes", "spades", "Spades" => $this->suite = "♠",
            default => $this->suite = "",
        };

        match ($suite) {
            "♥", "♡", "hearts", "Hearts" => $this->color = "red",
            "♦", "♢", "tiles", "Tiles", "diamonds", "Diamonds" => $this->color = "red",
            "♣", "♧", "clovers", "Clovers", "clubs", "Clubs" => $this->color = "black",
            "♠", "♤", "pikes", "Pikes", "spades", "Spades" => $this->color = "black",
            default => $this->suite = "",
        };
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getString(): string
    {
        return "[". $this->suite . $this->rank . "]";
    }
}
