<?php

namespace App\Tests\Game\BlackJack;

use App\Cards\Card;
use App\Game\BlackJack\Player;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
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
        $player = new Player();
        $this->assertInstanceOf(Player::class, $player);
    }

    /**
     * testBooleans
     *
     * Test the boolean values
     *
     * @return void
     */
    public function testBooleans(): void
    {
        $player = new Player();

        $card = new Card('K', 'Spades');

        $player->addCard($card);
        $player->addCard($card);

        $bust = $player->isBust();
        $this->assertFalse($bust);

        $player->addCard($card);

        $bust = $player->isBust();
        $this->assertTrue($bust);
    }

    /**
     * testGet
     *
     * Test the get function
     *
     * @return void
     */
    public function testGet(): void
    {
        $player = new Player();

        $card = new Card('K', 'Spades');

        $player->addCard($card);
        $player->addCard($card);

        $handValue = $player->getHandValue();
        $this->assertEquals(20, $handValue);
    }
}
