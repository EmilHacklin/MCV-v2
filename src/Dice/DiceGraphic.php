<?php

namespace App\Dice;

class DiceGraphic extends Dice
{
    public const GRAPHIC_REPRESENTATION = [
        '⚀',
        '⚁',
        '⚂',
        '⚃',
        '⚄',
        '⚅',
    ];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getAsString
     *
     * @return string
     */
    public function getAsString(): string
    {
        $value = $this->value;
        return ($value >= 1 and $value <= 6) ? self::GRAPHIC_REPRESENTATION[$value - 1] : parent::getAsString();
    }
}
