<?php

namespace App\Tests\Cards;

use PHPUnit\Framework\TestCase;
use App\Cards\CardGraphic;
use App\Cards\CardHand;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
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
        $cardHand = new CardHand();
        $this->assertInstanceOf(CardHand::class, $cardHand);

        $res = $cardHand->getString();
        $this->assertEmpty($res);
    }

    /**
     * testAddCard
     *
     * Test the add card function
     *
     * @return void
     */
    public function testAddCard(): void
    {
        $cardHand = new CardHand();

        $cardHand->addCard(new CardGraphic("a", "Heart"));
        $cardHand->addCard(new CardGraphic("king", "spade"));

        $res = $cardHand->getString();
        $this->assertEquals(['🂱', '🂮'], $res);
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
        $cardHand = new CardHand();

        $cardHand->addCard(new CardGraphic("a", "Heart"));
        $cardHand->addCard(new CardGraphic("king", "spade"));

        $cardCount = $cardHand->cardCount();
        $this->assertEquals(2, $cardCount);

        $value = $cardHand->getValue();
        $this->assertEquals(14, $value);

        $value = $cardHand->getValueAceHigh();
        $this->assertEquals(27, $value);

        $value = $cardHand->getBlackJackValue();
        $this->assertEquals(11, $value);

        $value = $cardHand->getBlackJackValueAceHigh();
        $this->assertEquals(21, $value);
    }
}
