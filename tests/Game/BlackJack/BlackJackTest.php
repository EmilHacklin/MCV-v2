<?php

namespace App\Tests\Game;

use App\Game\BlackJack;
use App\Game\BlackJack\Dealer;
use App\Game\BlackJack\Player;
use PHPUnit\Framework\TestCase;
use App\Cards\CardGraphic;

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
        $this->assertEquals("1", $res["numOfPlayers"]);
    }

    /**
     * testToFewPlayers
     *
     * @return void
     */
    public function testToFewPlayers(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Can't have less then one player in Black Jack");
        new BlackJack(0); // Less than 1
    }

    /**
     * testToManyPlayers
     *
     * @return void
     */
    public function testToManyPlayers(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Maximum of ' . BlackJack::MAX_PLAYERS . ' players in Black Jack');
        new BlackJack(BlackJack::MAX_PLAYERS + 1);
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

        $this->assertEquals(3, count($res["playersCards"][0]));

        while ($blackJack->isPlayerBust() === false) {
            $blackJack->hitPlayer();
        }

        $res = $blackJack->stateOfGame();
        $this->assertEquals("Dealer", $res["gameStates"][0]);

        $blackJack->hitPlayer(-1);
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

        $this->assertNotEquals("Undecided", $res["gameStates"][0]);

        $blackJack->stayPlayer(-1);
    }

    /**
     * testResetGame
     *
     * @return void
     */
    public function testResetGame(): void
    {
        $blackJack = new BlackJack();

        $blackJack->stayPlayer();

        $res = $blackJack->stateOfGame();

        $blackJack->resetGame();

        $res = $blackJack->stateOfGame();

        $this->assertEquals("Undecided", $res["gameStates"][0]);
    }

    /**
     * testDealerBust
     *
     * Test if dealer can be busted and player wins
     *
     * @return void
     */
    public function testDealerBust(): void
    {
        // Create mocks for Dealer and Player
        $dealerMock = $this->createMock(Dealer::class);
        $playerMock = $this->createMock(Player::class);

        // Set up the Player mock to return a hand value of 20 (not bust)
        $playerMock->method('getHandValue')->willReturn(20);
        $playerMock->method('isBust')->willReturn(false);
        $playerMock->method('getString')->willReturn(['🂡', '🂢']);

        // Set up the Dealer mock to return a hand value > 21 (bust)
        $dealerMock->method('getHandValue')->willReturn(22);
        $dealerMock->method('isBust')->willReturn(true);
        $dealerMock->method('getString')->willReturn(['🂠', '🂠']);

        $blackJack = new BlackJack();

        // Inject mocked Dealer and Player into the game
        $reflection = new \ReflectionClass($blackJack);

        // Set the dealer mock
        $dealerProperty = $reflection->getProperty('dealer');
        $dealerProperty->setAccessible(true);
        $dealerProperty->setValue($blackJack, $dealerMock);

        // Set the player mock
        $playersProperty = $reflection->getProperty('players');
        $playersProperty->setAccessible(true);
        $playersProperty->setValue($blackJack, [$playerMock]);

        // Invoke the private calculateWinner method via reflection
        $method = $reflection->getMethod('calculateWinner');
        $method->setAccessible(true);
        $method->invoke($blackJack, 0);

        // Get the game state
        $res = $blackJack->stateOfGame();

        // Assert dealer is bust
        $this->assertTrue($blackJack->isDealerBust());

        // Assert that the player wins since dealer busts
        $this->assertEquals(
            'Player',
            $res['gameStates'][0]
        );

    }

    /**
     * testPlayerWin
     *
     * @return void
     */
    public function testPlayerWin(): void
    {
        // Create mocks for Dealer and Player
        $dealerMock = $this->createMock(Dealer::class);
        $playerMock = $this->createMock(Player::class);

        // Set up the Player mock to return a hand value of 20
        $playerMock->method('getHandValue')->willReturn(20);
        $playerMock->method('isBust')->willReturn(false);
        $playerMock->method('getString')->willReturn(['🂡', '🂢']);

        // Set up the Dealer mock to return a hand value 17
        $dealerMock->method('getHandValue')->willReturn(17);
        $dealerMock->method('isBust')->willReturn(false);
        $dealerMock->method('getString')->willReturn(['🂠', '🂠']);

        $blackJack = new BlackJack();

        // Inject mocked Dealer and Player into the game
        $reflection = new \ReflectionClass($blackJack);

        // Set the dealer mock
        $dealerProperty = $reflection->getProperty('dealer');
        $dealerProperty->setAccessible(true);
        $dealerProperty->setValue($blackJack, $dealerMock);

        // Set the player mock
        $playersProperty = $reflection->getProperty('players');
        $playersProperty->setAccessible(true);
        $playersProperty->setValue($blackJack, [$playerMock]);

        // Invoke the private calculateWinner method via reflection
        $method = $reflection->getMethod('calculateWinner');
        $method->setAccessible(true);
        $method->invoke($blackJack, 0);

        // Get the game state
        $res = $blackJack->stateOfGame();

        $this->assertEquals('Player', $res['gameStates'][0]);
    }

    /**
     * testTie
     *
     * @return void
     */
    public function testTie(): void
    {
        // Create mocks for Dealer and Player
        $dealerMock = $this->createMock(Dealer::class);
        $playerMock = $this->createMock(Player::class);

        // Set up the Player mock to return a hand value of 20
        $playerMock->method('getHandValue')->willReturn(20);
        $playerMock->method('isBust')->willReturn(false);
        $playerMock->method('getString')->willReturn(['🂡', '🂢']);

        // Set up the Dealer mock to return a hand value 20
        $dealerMock->method('getHandValue')->willReturn(20);
        $dealerMock->method('isBust')->willReturn(false);
        $dealerMock->method('getString')->willReturn(['🂠', '🂠']);

        $blackJack = new BlackJack();

        // Inject mocked Dealer and Player into the game
        $reflection = new \ReflectionClass($blackJack);

        // Set the dealer mock
        $dealerProperty = $reflection->getProperty('dealer');
        $dealerProperty->setAccessible(true);
        $dealerProperty->setValue($blackJack, $dealerMock);

        // Set the player mock
        $playersProperty = $reflection->getProperty('players');
        $playersProperty->setAccessible(true);
        $playersProperty->setValue($blackJack, [$playerMock]);

        // Invoke the private calculateWinner method via reflection
        $method = $reflection->getMethod('calculateWinner');
        $method->setAccessible(true);
        $method->invoke($blackJack, 0);

        // Get the game state
        $res = $blackJack->stateOfGame();

        $this->assertEquals('Tie', $res['gameStates'][0]);
    }
}
