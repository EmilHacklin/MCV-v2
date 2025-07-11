<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;
use App\Cards\Card;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
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
        $card = new Card(Card::NO_RANK, Card::NO_SUIT);
        $this->assertInstanceOf(Card::class, $card);

        $res = $card->getString();
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
        $card = new Card("a", "Heart");

        $rank = $card->getRank();
        $this->assertEquals("A", $rank);

        $suit = $card->getSuit();
        $this->assertEquals('♥', $suit);

        $value = $card->getValue();
        $this->assertEquals(1, $value);
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
        $card = new Card("a", "Heart");

        $card->setRank('k');
        $rank = $card->getRank();
        $this->assertEquals("K", $rank);
        $value = $card->getValue();
        $this->assertEquals(13, $value);

        $card->setSuit('Wrong input');
        $suit = $card->getSuit();
        $this->assertEquals(Card::NO_SUIT, $suit);
        $value = $card->getValue();
        $this->assertEquals(0, $value);
    }
}
