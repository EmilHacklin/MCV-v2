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
     * testSettersAndGetters
     *
     * Test the setters and getters
     *
     * @return void
     */
    public function testSettersAndGetters(): void
    {
        $player = new Player("Player& Test", -15);

        $name = $player->getName();
        $this->assertEquals("Player&amp; Test", $name);

        $credits = $player->getCredits();
        $this->assertEquals(0, $credits);

        $bet = $player->getBet();
        $this->assertEquals(0, $bet);

        $states = [Player::DOUBLE_DOWN, player::STAYED, player::UNDECIDED, player::TIE, player::PLAYER_WIN, player::DEALER_WIN];

        foreach ($states as $state) {
            $player->setGameState($state);
            $currentState = $player->getGameState();
            $this->assertEquals($state, $currentState);
        }
    }

    /**
     * testChangeCredits
     *
     * Test the changeCredits function
     *
     * @return void
     */
    public function testChangeCredits(): void
    {
        $player = new Player("", 0);

        $player->changeCredits(-1);

        $balance = $player->getCredits();
        $this->assertEquals(0, $balance);

        $maxCredits = PHP_INT_MAX;
        $player->changeCredits(1);
        $player->changeCredits($maxCredits);

        $balance = $player->getCredits();
        $this->assertEquals($maxCredits, $balance);
    }

    /**
     * testBet
     *
     * Test the increaseBet and resetBet function
     *
     * @return void
     */
    public function testBet(): void
    {
        $player = new Player("Player", 100);

        $player->increaseBet(50);

        $credits = $player->getCredits();
        $this->assertEquals(50, $credits);

        $bet = $player->getBet();
        $this->assertEquals(50, $bet);

        //Test increase over available credits
        $player->increaseBet(100);

        $credits = $player->getCredits();
        $this->assertEquals(0, $credits);

        $bet = $player->getBet();
        $this->assertEquals(100, $bet);

        //Test reset bet
        $player->resetBet();

        $bet = $player->getBet();
        $this->assertEquals(0, $bet);
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

        $broke = $player->isBroke();
        $this->assertFalse($broke);

        $player->setCredits(0);

        $broke = $player->isBroke();
        $this->assertTrue($broke);
    }

    /**
     * testGetValue
     *
     * Test the GetValue function
     *
     * @return void
     */
    public function testGetValue(): void
    {
        $player = new Player();

        $card = new Card('K', 'Spades');

        $player->addCard($card);
        $player->addCard($card);

        $handValue = $player->getHandValue();
        $this->assertEquals(20, $handValue);
    }

    /**
     * testDropHand
     *
     * Test the dropHand function
     *
     * @return void
     */
    public function testDropHand(): void
    {
        $player = new Player();

        $card = new Card('K', 'Spades');

        $player->addCard($card);

        $numOfCards = count($player->getString());
        $this->assertEquals(1, $numOfCards);

        $player->dropHand();

        $numOfCards = count($player->getString());
        $this->assertEquals(0, $numOfCards);
    }
}
