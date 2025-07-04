<?php

namespace App\Dice;

class Dice
{
    protected int $value;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->value = 0;
    }

    /**
     * roll
     *
     * @return int
     */
    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->value;
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
     * getAsString
     *
     * @return string
     */
    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
