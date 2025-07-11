<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;
use App\Cards\CardGraphic;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
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
        $cardGraphic = new CardGraphic(CardGraphic::NO_RANK, CardGraphic::NO_SUIT);
        $this->assertInstanceOf(CardGraphic::class, $cardGraphic);

        $res = $cardGraphic->getString();
        $this->assertNotEmpty($res);
    }

    /**
     * testGetFunctions
     *
     * Test the get functions of the class
     *
     * @return void
     */
    public function testGetFunctions(): void
    {
        $cardGraphic = new CardGraphic("a", "Heart");

        $rank = $cardGraphic->getRank();
        $this->assertEquals("A", $rank);

        $suit = $cardGraphic->getSuit();
        $this->assertEquals('♥', $suit);

        $res = $cardGraphic->getString();
        $this->assertEquals('🂱', $res);
    }

    /**
     * testSetFunctions
     *
     * Test the set functions of the class
     *
     * @return void
     */
    public function testSetFunctions(): void
    {
        $cardGraphic = new CardGraphic("a", "Heart");

        $cardGraphic->setRank('k');
        $rank = $cardGraphic->getRank();
        $this->assertEquals("K", $rank);
        $res = $cardGraphic->getString();
        $this->assertEquals('🂾', $res);

        $cardGraphic->setSuit('Wrong input');
        $suit = $cardGraphic->getSuit();
        $this->assertEquals(CardGraphic::NO_SUIT, $suit);
        $res = $cardGraphic->getString();
        $this->assertEquals(CardGraphic::BLANK_CARD, $res);
    }
}
