<?php

namespace App\Tests\Game\BlackJack;

use App\Cards\Card;
use App\Game\BlackJack\Dealer;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dealer.
 */
class DealerTest extends TestCase
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
        $dealer = new Dealer();
        $this->assertInstanceOf(Dealer::class, $dealer);
    }

    /**
     * testPlay
     *
     * Test the Play function
     *
     * @return void
     */
    public function testPlay(): void
    {
        $dealer = new Dealer();

        $card = new Card('2', 'Spades');

        $dealer->addCard($card);
        $dealer->addCard($card);

        while (true === $dealer->Play()) {
            $handValue = $dealer->getHandValue();
            $this->assertLessThan(17, $handValue);
            $dealer->addCard($card);
        }

        $handValue = $dealer->getHandValue();
        $this->assertGreaterThan(16, $handValue);
    }
}
