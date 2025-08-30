<?php

namespace App\Tests\Game\BlackJack;

use App\Cards\Card;
use App\Game\BlackJack;
use App\Game\BlackJack\GameLogic;
use App\Game\BlackJack\Dealer;
use App\Game\BlackJack\Player;
use PHPUnit\Framework\TestCase;
use App\Cards\CardGraphic;

/**
 * Test cases for class GameLogic.
 */
class GameLogicTest extends TestCase
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
        $blackJack = new BlackJack(1);
        $this->assertInstanceOf(BlackJack::class, $blackJack);

        $gameLogic = new GameLogic($blackJack);
        $this->assertInstanceOf(GameLogic::class, $gameLogic);
    }

    /**
     * testNewGame
     *
     * Test the newGame function
     *
     * @return void
     */
    public function testNewGame(): void
    {
        $blackJack = new BlackJack(1);
        $gameLogic = new GameLogic($blackJack);

        $gameLogic->newGame();

        $player = $blackJack->getPlayers()[0];
        $dealer = $blackJack->getDealer();

        $this->assertEquals(2, count($player->getString()));
        $this->assertEquals(2, count($dealer->getString()));
        $this->assertEquals(BlackJack::MINIMUM_BET, $player->getBet());

        //Check if player broke new game
        //Bet all
        $player->increaseBet(Player::DEFAULT_STARTING_CREDITS - BlackJack::MINIMUM_BET);
        $blackJack->setPlayers([$player]);

        //Reset game so player have 0 credits and no bet === broke
        $gameLogic->newGame();

        $player = $blackJack->getPlayers()[0];
        $dealer = $blackJack->getDealer();

        $this->assertEquals(0, count($player->getString()));
        $this->assertEquals(2, count($dealer->getString()));
        $this->assertEquals(0, $player->getBet());
    }

    /**
     * testStateOfGameAndCheckIfDealersTurn
     *
     * Test the stateOfGame and checkIfDealersTurn function
     *
     * @return void
     */
    public function testStateOfGameAndCheckIfDealersTurn(): void
    {
        $blackJack = new BlackJack();
        $gameLogic = new GameLogic($blackJack);

        $gameLogic->newGame();

        $gameLogic->checkIfDealersTurn();

        $data = $gameLogic->stateOfGame();

        $this->assertEquals(1, $data["numOfPlayers"]);
        $this->assertEquals(Player::DEFAULT_PLAYER_NAME, $data["playersNames"][0]);
        $this->assertEquals(2, count($data["playersCards"][0]));
        $this->assertEquals(1, count($data["playersHandValue"]));
        $this->assertEquals(strval(Player::DEFAULT_STARTING_CREDITS - BlackJack::MINIMUM_BET), $data["playersCredits"][0]);
        $this->assertEquals(strval(BlackJack::MINIMUM_BET), $data["playersBets"][0]);
        $this->assertEquals(2, count($data["dealerCards"]));
        $this->assertEquals('🂠', $data["dealerCards"][1]);
        $this->assertEquals('0', $data["dealerHandValue"]);
        $this->assertEquals('Undecided', $data["gameStates"][0]);

        //If game is decided.
        $player = $blackJack->getPlayers()[0];
        $player->setGameState(Player::STAYED);
        $blackJack->setPlayers([$player]);

        $gameLogic->checkIfDealersTurn();

        $data = $gameLogic->stateOfGame();

        $this->assertEquals(1, $data["numOfPlayers"]);
        $this->assertEquals(Player::DEFAULT_PLAYER_NAME, $data["playersNames"][0]);
        $this->assertEquals(2, count($data["playersCards"][0]));
        $this->assertEquals(1, count($data["playersHandValue"]));
        $this->assertGreaterThanOrEqual(strval(Player::DEFAULT_STARTING_CREDITS - BlackJack::MINIMUM_BET), $data["playersCredits"][0]);
        $this->assertEquals('0', $data["playersBets"][0]);
        $this->assertGreaterThanOrEqual(2, count($data["dealerCards"]));
        $this->assertNotEquals('🂠', $data["dealerCards"][1]);
        $this->assertNotEquals('0', $data["dealerHandValue"]);
        $this->assertNotEquals('Undecided', $data["gameStates"][0]);
    }

    /**
     * testPlayerBust
     *
     * Test if player is busted and dealers wins
     *
     * @return void
     */
    public function testPlayerBust(): void
    {
        $player = new Player();
        $player->setGameState(Player::DEALER_WIN);
        $player->addCard(new Card("King", "Spade"));
        $player->addCard(new Card("King", "Spade"));
        $player->addCard(new Card("King", "Spade"));

        $dealer = new Dealer();
        $dealer->addCard(new Card("10", "Heart"));
        $dealer->addCard(new Card("10", "Heart"));

        // Create BlackJack instance
        $blackJack = new BlackJack(1);

        $blackJack->setPlayers([$player]);
        $blackJack->setDealer($dealer);

        $gameLogic = new GameLogic($blackJack);

        $gameLogic->checkIfDealersTurn();

        // Get the game state
        $data = $gameLogic->stateOfGame();

        // Assert player is bust
        $this->assertTrue($blackJack->isPlayerBust());

        // Assert that the dealer wins since player busts
        $this->assertEquals('Dealer', $data['gameStates'][0]);

        //Check if player is bust but BlackJack code is broken
        $player->setGameState(Player::STAYED);
        $blackJack->setPlayers([$player]);

        $gameLogic->checkIfDealersTurn();

        // Get the game state
        $data = $gameLogic->stateOfGame();

        // Assert player is bust
        $this->assertTrue($blackJack->isPlayerBust());

        // Assert that the dealer wins since player busts
        $this->assertEquals('Dealer', $data['gameStates'][0]);
    }

    /**
     * testDealerBust
     *
     * Test if dealer is busted and player wins
     *
     * @return void
     */
    public function testDealerBust(): void
    {
        $player = new Player();
        $player->setGameState(Player::STAYED);
        $player->addCard(new Card("A", "Spade"));
        $player->addCard(new Card("King", "Spade"));

        $dealer = new Dealer();
        $dealer->addCard(new Card("10", "Heart"));
        $dealer->addCard(new Card("10", "Heart"));
        $dealer->addCard(new Card("10", "Heart"));

        // Create BlackJack instance
        $blackJack = new BlackJack(1);

        $blackJack->setPlayers([$player]);
        $blackJack->setDealer($dealer);

        $gameLogic = new GameLogic($blackJack);

        $gameLogic->checkIfDealersTurn();

        // Get the game state
        $data = $gameLogic->stateOfGame();

        // Assert dealer is bust
        $this->assertTrue($blackJack->isDealerBust());

        // Assert that the player wins since dealer busts
        $this->assertEquals('Player', $data['gameStates'][0]);
    }

    /**
     * testPlayerWin
     *
     * @return void
     */
    public function testPlayerWin(): void
    {
        $player = new Player();
        $player->setGameState(Player::STAYED);
        $player->addCard(new Card("A", "Spade"));
        $player->addCard(new Card("King", "Spade"));

        $dealer = new Dealer();
        $dealer->addCard(new Card("10", "Heart"));
        $dealer->addCard(new Card("10", "Heart"));

        // Create BlackJack instance
        $blackJack = new BlackJack();

        $blackJack->setPlayers([$player]);
        $blackJack->setDealer($dealer);

        $gameLogic = new GameLogic($blackJack);

        $gameLogic->checkIfDealersTurn();

        // Get the game state
        $data = $gameLogic->stateOfGame();

        // Assert that the player wins since higher value then dealer
        $this->assertEquals('Player', $data['gameStates'][0]);
    }

    /**
     * testDealerWin
     *
     * @return void
     */
    public function testDealerWin(): void
    {
        $player = new Player();
        $player->setGameState(Player::STAYED);
        $player->addCard(new Card("10", "Spade"));
        $player->addCard(new Card("King", "Spade"));

        $dealer = new Dealer();
        $dealer->addCard(new Card("A", "Heart"));
        $dealer->addCard(new Card("10", "Heart"));

        // Create BlackJack instance
        $blackJack = new BlackJack();

        $blackJack->setPlayers([$player]);
        $blackJack->setDealer($dealer);

        $gameLogic = new GameLogic($blackJack);

        $gameLogic->checkIfDealersTurn();

        // Get the game state
        $data = $gameLogic->stateOfGame();

        // Assert that the dealer wins since higher value then player
        $this->assertEquals('Dealer', $data['gameStates'][0]);
    }

    /**
     * testTie
     *
     * @return void
     */
    public function testTie(): void
    {
        $player = new Player();
        $player->setGameState(Player::STAYED);
        $player->addCard(new Card("A", "Spade"));
        $player->addCard(new Card("King", "Spade"));

        $dealer = new Dealer();
        $dealer->addCard(new Card("A", "Heart"));
        $dealer->addCard(new Card("10", "Heart"));

        // Create BlackJack instance
        $blackJack = new BlackJack();

        $blackJack->setPlayers([$player]);
        $blackJack->setDealer($dealer);

        $gameLogic = new GameLogic($blackJack);

        $gameLogic->checkIfDealersTurn();

        // Get the game state
        $data = $gameLogic->stateOfGame();

        // Assert that it is a tie since dealer and player have same value
        $this->assertEquals('Tie', $data['gameStates'][0]);
    }
}
