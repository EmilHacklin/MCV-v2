<?php

namespace App\Tests\Dice;

use PHPUnit\Framework\TestCase;
use App\Dice\DiceGraphic;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * testCreateObject
     *
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     *
     * @return void
     */
    public function testCreateObject(): void
    {
        $diceGraphic = new DiceGraphic();
        $this->assertInstanceOf(DiceGraphic::class, $diceGraphic);

        $res = $diceGraphic->getString();
        $this->assertNotEmpty($res);
    }

    /**
     * testGetString
     *
     * Test the toString method
     *
     * @return void
     */
    public function testGetString(): void
    {
        $stub = $this->createMock(DiceGraphic::class);

        // Configure the stub.
        $stub->method('getString')
            ->willReturn('⚅');

        $this->assertEquals('⚅', $stub->getString());
    }
}
