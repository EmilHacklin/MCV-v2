<?php

namespace App\Cards;

/**
 * CardGraphic.
 */
class CardGraphic extends Card
{
    /**
     * Constant array containing the UTF-8 characters for the cards in a card playing deck.
     */
    public const GRAPHIC_REPRESENTATION = [
        'A' => ['♥' => '🂱', '♦' => '🃁', '♣' => '🃑', '♠' => '🂡'],
        '2' => ['♥' => '🂲', '♦' => '🃂', '♣' => '🃒', '♠' => '🂢'],
        '3' => ['♥' => '🂳', '♦' => '🃃', '♣' => '🃓', '♠' => '🂣'],
        '4' => ['♥' => '🂴', '♦' => '🃄', '♣' => '🃔', '♠' => '🂤'],
        '5' => ['♥' => '🂵', '♦' => '🃅', '♣' => '🃕', '♠' => '🂥'],
        '6' => ['♥' => '🂶', '♦' => '🃆', '♣' => '🃖', '♠' => '🂦'],
        '7' => ['♥' => '🂷', '♦' => '🃇', '♣' => '🃗', '♠' => '🂧'],
        '8' => ['♥' => '🂸', '♦' => '🃈', '♣' => '🃘', '♠' => '🂨'],
        '9' => ['♥' => '🂹', '♦' => '🃉', '♣' => '🃙', '♠' => '🂩'],
        '10' => ['♥' => '🂺', '♦' => '🃊', '♣' => '🃚', '♠' => '🂪'],
        'J' => ['♥' => '🂻', '♦' => '🃋', '♣' => '🃛', '♠' => '🂫'],
        'C' => ['♥' => '🂼', '♦' => '🃌', '♣' => '🃜', '♠' => '🂬'],
        'Q' => ['♥' => '🂽', '♦' => '🃍', '♣' => '🃝', '♠' => '🂭'],
        'K' => ['♥' => '🂾', '♦' => '🃎', '♣' => '🃞', '♠' => '🂮'],
        'Joker' => ['Red' => '🂿', 'Black' => '🃏︎', 'White' => '🃟'],
    ];

    /**
     * Constant character for blank card.
     */
    public const BLANK_CARD = '🂠';

    private string $graphic;

    /**
     * __construct.
     *
     * Constructor of the class
     *
     * @return void
     */
    public function __construct(string $rank, string $suite)
    {
        parent::__construct($rank, $suite);

        $this->findGraphic();
    }

    /**
     * findGraphic.
     *
     * Finds graphic char that matches the cards rank and suite
     */
    private function findGraphic(): void
    {
        (Card::NO_RANK == $this->rank or Card::NO_SUIT == $this->suit) ?
        $this->graphic = self::BLANK_CARD :
        $this->graphic = self::GRAPHIC_REPRESENTATION[$this->rank][$this->suit];
    }

    /**
     * setRank.
     *
     * Sets the rank of the card and updates the graphic
     */
    public function setRank(string $rank): void
    {
        parent::setRank($rank);

        $this->findGraphic();
    }

    /**
     * setSuite.
     *
     * Sets the suite of the card and updates the graphic
     */
    public function setSuit(string $suit): void
    {
        parent::setSuit($suit);

        $this->findGraphic();
    }

    /**
     * getString.
     *
     * Returns the graphic
     */
    public function getString(): string
    {
        return $this->graphic;
    }
}
