<?php

namespace App\Tests\Game;

use App\Cards\Card;
use App\Cards\DeckOfCards;
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

        $blackJack->newGame();

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

        $blackJack->newGame();

        $playerBust = $blackJack->isPlayerBust();
        $this->assertEquals(false, $playerBust);

        $playerBroke = $blackJack->isPlayerBroke();
        $this->assertEquals(false, $playerBroke);

        $dealerBust = $blackJack->isDealerBust();
        $this->assertEquals(false, $dealerBust);

        //Test if index out of bounds
        $playerBust = $blackJack->isPlayerBust(-1);
        $this->assertEquals(true, $playerBust);

        $playerBroke = $blackJack->isPlayerBroke(-1);
        $this->assertEquals(true, $playerBroke);
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
        $blackJack = new BlackJack();

        $deck = new DeckOfCards();
        $deck->drawCard();

        //Test setDeck
        $blackJack->setDeck($deck);

        //Test get
        $deckCopy = $blackJack->getDeck();
        $this->assertEquals(51, count($deckCopy->getString()));

        //Test setPlayers
        $player = new Player();
        $player->setGameState(Player::STAYED);
        $player->addCard(new Card("A", "Spade"));
        $player->addCard(new Card("King", "Spade"));

        $blackJack->setPlayers([$player, $player]);

        //Test getPlayers
        $players = $blackJack->getPlayers();
        $numOfPlayers = $blackJack->getNumOfPlayers();
        $this->assertEquals(2, count($players));
        $this->assertEquals(2, $numOfPlayers);

        //Test setPlayer
        $players[1]->addCard(new Card("A", "Spade"));
        $blackJack->setPlayer(1, $players[1]);

        $players = $blackJack->getPlayers();
        $numOfPlayers = $blackJack->getNumOfPlayers();
        $this->assertEquals(2, count($players));
        $this->assertEquals(2, $numOfPlayers);

        //Test setPlayer out of bounds
        $blackJack->setPlayer(2, $player);

        $players = $blackJack->getPlayers();
        $this->assertEquals(3, count($players[1]->getString()));

        //Test setDealer
        $dealer = new Dealer();
        $dealer->addCard(new Card("10", "Heart"));
        $dealer->addCard(new Card("10", "Heart"));

        $blackJack->setDealer($dealer);

        //Test getDealer
        $dealerCopy = $blackJack->getDealer();
        $this->assertEquals(2, count($dealerCopy->getString()));

        //Check if to many players
        $players = [];
        for ($i = 0; $i <= BlackJack::MAX_PLAYERS; $i++) {
            $players[] = $player;
        }

        $blackJack->setPlayers([$player]);

        $players = $blackJack->getPlayers();
        $numOfPlayers = $blackJack->getNumOfPlayers();
        $this->assertEquals(1, count($players));
        $this->assertEquals(1, $numOfPlayers);
    }

    /**
     * testStayPlayer
     *
     * Test the stay action of the player
     *
     * @return void
     */
    public function testStayPlayer(): void
    {
        $blackJack = new BlackJack();

        $blackJack->newGame();

        $blackJack->stayPlayer();

        $res = $blackJack->stateOfGame();

        $this->assertNotEquals("Undecided", $res["gameStates"][0]);

        $blackJack->stayPlayer(-1);
    }

    /**
     * testHitPlayer
     *
     * Test the hit action of the player
     *
     * @return void
     */
    public function testHitPlayer(): void
    {
        $blackJack = new BlackJack();

        $blackJack->newGame();

        $blackJack->hitPlayer();

        $player = $blackJack->getPlayers()[0];

        $this->assertEquals(3, count($player->getString()));

        //Check if gone bust
        while ($blackJack->isPlayerBust() === false) {
            $blackJack->hitPlayer();
        }

        $player = $blackJack->getPlayers()[0];
        $this->assertEquals(0, $player->getBet());

        //Check if index out of bound
        $blackJack->hitPlayer(-1);
    }

    /**
     * testDoubleDownPlayer
     *
     * Test the double down action of the player
     *
     * @return void
     */
    public function testDoubleDownPlayer(): void
    {
        $blackJack = new BlackJack();

        $blackJack->newGame();

        $blackJack->doubleDownPlayer();

        $player = $blackJack->getPlayers()[0];

        $this->assertEquals(3, count($player->getString()));

        (true === $player->isBust()) ?
            $this->assertEquals(Player::DEFAULT_STARTING_CREDITS - (BlackJack::MINIMUM_BET * 2), $player->getCredits()) :
            $this->assertGreaterThanOrEqual(Player::DEFAULT_STARTING_CREDITS - (BlackJack::MINIMUM_BET * 2), $player->getCredits());

        $blackJack->newGame();

        $blackJack->doubleDownPlayer(-1);
    }
}
