<?php

namespace App\Dice;

class Dice
{
    protected int $value;

    /**
     * __construct.
     *
     * Constructor of the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->value = random_int(1, 6);
    }

    /**
     * roll.
     *
     * Roll a new value for the dice (1-6)
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);

        return $this->value;
    }

    /**
     * getValue.
     *
     * Returns the value of the dice
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * getString.
     *
     * Returns the value of the dice as a string
     */
    public function getString(): string
    {
        return "[{$this->value}]";
    }
}
