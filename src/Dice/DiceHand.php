<?php

namespace App\Dice;

use App\Dice\Dice;

class DiceHand
{
    /**
    * @var array<Dice> $hand
    * Is an array that contains the Dice objects
    */
    private array $hand = [];

    /**
     * addDie
     *
     * Add a die to the hand
     *
     * @param  Dice $die
     * @return void
     */
    public function addDie(Dice $die): void
    {
        $this->hand[] = $die;
    }

    /**
     * removeDice
     *
     * Remove the top dice from hand
     *
     * @return void
     */
    public function removeDie(): void
    {
        if (Count($this->hand) > 0) {
            array_shift($this->hand);
        }
    }

    /**
     * roll
     *
     * Roll all the Dice in the hand
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
     * Return the number of dice in the hand
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
     * Return an array of all the Dice values in hand
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
     * sum
     *
     * Returns the sum of all Dice values in hand
     *
     * @return int
     */
    public function sum(): int
    {
        $sum = 0;
        foreach ($this->hand as $die) {
            $sum += $die->getValue();
        }
        return $sum;
    }

    /**
     * getString
     *
     * Returns an string array containing all the Dice
     *
     * @return array<string>
     */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $die) {
            $values[] = $die->getString();
        }
        return $values;
    }
}
