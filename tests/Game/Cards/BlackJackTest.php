<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;
use App\Cards\CardGraphic;
use App\Cards\BlackJack;

/**
 * Test cases for class BlackJack.
 */
class BlackJackTest extends TestCase
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
        $blackJack = new BlackJack();
        $this->assertInstanceOf(BlackJack::class, $blackJack);

        $res = $blackJack->stateOfGame();
        $this->assertEquals("Undecided", $res["winner"]);
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
        $blackJack = new BlackJack();

        $playerBust = $blackJack->isPlayerBust();
        $this->assertEquals(false, $playerBust);

        $dealerBust = $blackJack->isDealerBust();
        $this->assertEquals(false, $dealerBust);
    }

    /**
     * testHit
     *
     * Test the hit action of the player
     *
     * @return void
     */
    public function testHit(): void
    {
        $blackJack = new BlackJack();

        $blackJack->hitPlayer();
        $res = $blackJack->stateOfGame();
        $this->assertEquals(3, count((array)$res["player"]));

        while ($blackJack->isPlayerBust() == false) {
            $blackJack->hitPlayer();
        }

        $res = $blackJack->stateOfGame();
        $this->assertEquals("Dealer", $res["winner"]);
    }

    /**
     * testStay
     *
     * Test the stay action of the player
     *
     * @return void
     */
    public function testStay(): void
    {
        $blackJack = new BlackJack();

        $blackJack->stayPlayer();
        $res = $blackJack->stateOfGame();
        $this->assertNotEquals("Undecided", $res["winner"]);
    }

    public function testEndState(): void
    {
        $blackJack = new BlackJack();

        $dealerBust = false;
        $tie = false;
        $playerWins = false;

        do {
            $blackJack->stayPlayer();
            $res = $blackJack->stateOfGame();
            if ($blackJack->isDealerBust()) {
                $dealerBust = true;
                $this->assertEquals("Player", $res["winner"]);
            }
            if ($res["winner"] == "Tie") {
                $tie = true;
                $this->assertEquals("Tie", $res["winner"]);
            }
            if ($res["winner"] == "Player" and $blackJack->isDealerBust() == false) {
                $playerWins = true;
                $this->assertEquals("Player", $res["winner"]);
            }
            $blackJack->resetGame();
        } while ($dealerBust == false or $tie == false or $playerWins == false);

        $blackJack->resetGame();
        $res = $blackJack->stateOfGame();
        $this->assertEquals("Undecided", $res["winner"]);
    }
}
