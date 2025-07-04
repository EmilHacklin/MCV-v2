<?php

namespace App\Dice;

use App\Dice\Dice;

class DiceHand
{
    /**
    * @var array<Dice> $hand
    */
    private array $hand = [];

    /**
     * add
     *
     * @param  Dice $die
     * @return void
     */
    public function add(Dice $die): void
    {
        $this->hand[] = $die;
    }

    /**
     * roll
     *
     * @return void
     */
    public function roll(): void
    {
        foreach ($this->hand as $die) {
            $die->roll();
        }
    }

    /**
     * getNumberDices
     *
     * @return int
     */
    public function getNumberDices(): int
    {
        return count($this->hand);
    }

    /**
     * getValues
     *
     * @return array<int>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getValue();
        }
        return $values;
    }

    /**
     * getString
     *
     * @return array<string>
     */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getAsString();
        }
        return $values;
    }
}
