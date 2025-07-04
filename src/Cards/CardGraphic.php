<?php

namespace App\Cards;

/**
 * CardGraphic
 */
class CardGraphic extends Card
{
    private string $graphic;
    public const GRAPHIC_REPRESENTATION = [
        "A" => ['♥' => '🂱', '♦' => '🃁', '♣' => '🃑', '♠' => '🂡'],
        "2" => ['♥' => '🂲', '♦' => '🃂', '♣' => '🃒', '♠' => '🂢'],
        "3" => ['♥' => '🂳', '♦' => '🃃', '♣' => '🃓', '♠' => '🂣'],
        "4" => ['♥' => '🂴', '♦' => '🃄', '♣' => '🃔', '♠' => '🂤'],
        "5" => ['♥' => '🂵', '♦' => '🃅', '♣' => '🃕', '♠' => '🂥'],
        "6" => ['♥' => '🂶', '♦' => '🃆', '♣' => '🃖', '♠' => '🂦'],
        "7" => ['♥' => '🂷', '♦' => '🃇', '♣' => '🃗', '♠' => '🂧'],
        "8" => ['♥' => '🂸', '♦' => '🃈', '♣' => '🃘', '♠' => '🂨'],
        "9" => ['♥' => '🂹', '♦' => '🃉', '♣' => '🃙', '♠' => '🂩'],
        "10" => ['♥' => '🂺', '♦' => '🃊', '♣' => '🃚', '♠' => '🂪'],
        "J" => ['♥' => '🂻', '♦' => '🃋', '♣' => '🃛', '♠' => '🂫'],
        "C" => ['♥' => '🂼', '♦' => '🃌', '♣' => '🃜', '♠' => '🂬'],
        "Q" => ['♥' => '🂽', '♦' => '🃍', '♣' => '🃝', '♠' => '🂭'],
        "K" => ['♥' => '🂾', '♦' => '🃎', '♣' => '🃞', '♠' => '🂮'],
    ];

    /**
     * __construct
     *
     * @param  string $rank
     * @param  string $suite
     * @return void
     */
    public function __construct(string $rank, string $suite)
    {
        parent::__construct($rank, $suite);

        $this->findGraphic();
    }

    /**
     * findGraphic
     *
     * @return void
     */
    private function findGraphic(): void
    {
        ($this->rank == "no rank" or $this->suite == "no suite") ?
        $this->graphic = "🂠" :
        $this->graphic = self::GRAPHIC_REPRESENTATION[$this->rank][$this->suite];
    }

    /**
     * setRank
     *
     * @param  string $rank
     * @return void
     */
    public function setRank(string $rank): void
    {
        parent::setRank($rank);

        $this->findGraphic();
    }

    /**
     * setSuite
     *
     * @param  string $suite
     * @return void
     */
    public function setSuite(string $suite): void
    {
        parent::setSuite($suite);

        $this->findGraphic();
    }

    /**
     * getString
     *
     * @return string
     */
    public function getString(): string
    {
        return $this->graphic;
    }
}
