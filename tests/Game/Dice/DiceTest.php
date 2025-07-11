<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;
use App\Dice\Dice;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * testCreateDice
     *
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     *
     * @return void
     */
    public function testCreateObject(): void
    {
        $dice = new Dice();
        $this->assertInstanceOf(Dice::class, $dice);

        $res = $dice->getString();
        $this->assertNotEmpty($res);
    }


    /**
     * testStubRollDiceValue
     *
     * Create a mocked object that always returns 6.
     *
     * @return void
     */
    public function testStubRollValue()
    {
        // Create a stub for the Dice class.
        $stub = $this->createMock(Dice::class);

        // Configure the stub.
        $stub->method('roll')
            ->willReturn(6);

        $res = $stub->roll();
        $exp = 6;
        $this->assertEquals($exp, $res);
    }

    /**
     * testRollDiceValueInRange
     *
     * Roll dice and check the value is in range.
     *
     * @return void
     */
    public function testRollDiceValueInRange(): void
    {
        $dice = new Dice();
        $res = $dice->roll();
        $this->assertGreaterThanOrEqual(1, $res);
        $this->assertLessThanOrEqual(6, $res);
    }

    /**
     * testRollDiceValue
     *
     * Roll dice and check the value is same as value.
     *
     * @return void
     */
    public function testRollDiceValue(): void
    {
        $dice = new Dice();
        $exp = $dice->roll();
        $res = $dice->getValue();
        $this->assertEquals($exp, $res);
    }

    /**
     * testCreateObjectWithMock
     *
     * Construct a mock object of the class Dice and set consecutive values
     * to assert against.
     *
     * @return void
     */
    public function testCreateObjectWithMock(): void
    {
        // Create a stub for the SomeClass class.
        $stub = $this->createMock(Dice::class);

        // Configure the stub.
        $stub->method('getValue')
            ->willReturn(6);
        $stub->method('roll')
             ->willReturnOnConsecutiveCalls(2, 3, 5);

        // $stub->doSomething() returns a different value each time
        $this->assertEquals(6, $stub->getValue());
        $this->assertEquals(2, $stub->roll());
        $this->assertEquals(3, $stub->roll());
        $this->assertEquals(5, $stub->roll());
    }
}
