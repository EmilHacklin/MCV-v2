<?php

namespace App\Tests\Dice;

use App\Dice\Dice;
use App\Dice\DiceHand;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * testCreateObject
     *
     * Construct object and verify that the object has the expected
     * properties.
     *
     * @return void
     */
    public function testCreateObject(): void
    {
        $diceHand = new DiceHand();
        $this->assertInstanceOf(DiceHand::class, $diceHand);
    }

    /**
     * testAddDice
     *
     * Add dice, Roll the dice hand and the sum of the values are in range.
     *
     * @return void
     */
    public function testAddDice(): void
    {
        // Create a stub for the Dice class.
        $stub = $this->createMock(Dice::class);

        // Configure the stub.
        $stub->method('roll')
            ->willReturn(6);
        $stub->method('getValue')
            ->willReturn(6);

        $diceHand = new DiceHand();
        $diceHand->addDie(clone $stub);
        $diceHand->addDie(clone $stub);
        $diceHand->roll();
        $res = $diceHand->sum();
        $this->assertEquals(12, $res);
    }

    /**
     * testRemoveDice
     *
     * Remove dice, check how many dice is left
     *
     * @return void
     */
    public function testRemoveDice(): void
    {
        $diceHand = new DiceHand();
        $diceHand->addDie(new Dice());
        $diceHand->addDie(new Dice());
        $diceHand->addDie(new Dice());
        $numDice = $diceHand->getNumberDices();
        $this->assertEquals(3, $numDice);
        $diceHand->removeDie();
        $diceHand->removeDie();
        $numDice = $diceHand->getNumberDices();
        $this->assertEquals(1, $numDice);
    }

    /**
     * testGetValues
     *
     * Stub the dice class to return predictable values, then check get value
     *
     * @return void
     */
    public function testGetValues(): void
    {
        // Create a stub for the Dice class.
        $stub = $this->createMock(Dice::class);

        // Configure the stub.
        $stub->method('getValue')
            ->willReturn(6);

        $diceHand = new DiceHand();
        $diceHand->addDie(clone $stub);
        $diceHand->addDie(clone $stub);
        $res = $diceHand->getValues();
        $this->assertEquals([6,6], $res);
    }

    /**
     * testGetString
     *
     * Stub the dice class to return predictable values, then check get string
     *
     * @return void
     */
    public function testGetString(): void
    {
        // Create a stub for the Dice class.
        $stub = $this->createMock(Dice::class);

        // Configure the stub.
        $stub->method('getString')
            ->willReturn("6");

        $diceHand = new DiceHand();
        $diceHand->addDie(clone $stub);
        $diceHand->addDie(clone $stub);
        $res = $diceHand->getString();
        $this->assertEquals(["6","6"], $res);
    }
}
