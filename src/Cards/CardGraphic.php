<?php

namespace App\Cards;

class CardGraphic extends Card
{
    private $graphic;
    private $graphicsArray = [
        1 => ['♥' => '🂱', '♦' => '🃁', '♣' => '🃑', '♠' => '🂡'],
        2 => ['♥' => '🂲', '♦' => '🃂', '♣' => '🃒', '♠' => '🂢'],
        3 => ['♥' => '🂳', '♦' => '🃃', '♣' => '🃓', '♠' => '🂣'],
        4 => ['♥' => '🂴', '♦' => '🃄', '♣' => '🃔', '♠' => '🂤'],
        5 => ['♥' => '🂵', '♦' => '🃅', '♣' => '🃕', '♠' => '🂥'],
        6 => ['♥' => '🂶', '♦' => '🃆', '♣' => '🃖', '♠' => '🂦'],
        7 => ['♥' => '🂷', '♦' => '🃇', '♣' => '🃗', '♠' => '🂧'],
        8 => ['♥' => '🂸', '♦' => '🃈', '♣' => '🃘', '♠' => '🂨'],
        9 => ['♥' => '🂹', '♦' => '🃉', '♣' => '🃙', '♠' => '🂩'],
        10 => ['♥' => '🂺', '♦' => '🃊', '♣' => '🃚', '♠' => '🂪'],
        11 => ['♥' => '🂻', '♦' => '🃋', '♣' => '🃛', '♠' => '🂫'],
        12 => ['♥' => '🂽', '♦' => '🃍', '♣' => '🃝', '♠' => '🂭'],
        13 => ['♥' => '🂾', '♦' => '🃎', '♣' => '🃞', '♠' => '🂮'],
    ];

    public function __construct($rank, $suite)
    {
        parent::__construct($rank, $suite);

        $this->findGraphic();
    }

    private function findGraphic()
    {
        $this->graphic = $this->graphicsArray[$this->value][$this->suite];
    }

    public function setRank($rank)
    {
        parent::setRank($rank);

        $this->findGraphic();
    }

    public function setSuite($suite)
    {
        parent::setSuite($suite);

        $this->findGraphic();
    }

    public function getString(): string
    {
        return $this->graphic;
    }
}
