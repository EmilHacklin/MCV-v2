<?php

namespace App\Dice;

class DiceGraphic extends Dice
{
    /**
     * Constant array that contain the graphic representation of a six sided dice.
     */
    public const GRAPHIC_REPRESENTATION = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    /**
     * __construct.
     *
     * Constructor of the class
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getString.
     *
     * Return the value of the dice as a graphic representation of a six sided dice
     */
    public function getString(): string
    {
        $value = $this->value;

        return ($value >= 1 and $value <= 6) ? self::GRAPHIC_REPRESENTATION[$value - 1] : parent::getString();
    }
}
