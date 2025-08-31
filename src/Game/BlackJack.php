<?php

namespace App\Game;

use App\Cards\DeckOfCards;
use App\Game\BlackJack\Dealer;
use App\Game\BlackJack\GameLogic;
use App\Game\BlackJack\Player;

/**
 * BlackJack.
 */
class BlackJack
{
    public const MAX_PLAYERS = 7;
    public const MINIMUM_BET = 50;

    private GameLogic $gameLogic;
    private int $numOfPlayers;
    /**
     * @var array<Player> Is an array that contains BlackJackPlayer objects
     */
    private array $players;
    private Dealer $dealer;
    private DeckOfCards $deck;

    /**
     * __construct.
     *
     * Constructor of the class
     *
     * @return void
     */
    public function __construct(int $numOfPlayers = 1)
    {
        if ($numOfPlayers < 1) {
            throw new \RuntimeException("Can't have less then one player in Black Jack");
        }

        if ($numOfPlayers > self::MAX_PLAYERS) {
            throw new \RuntimeException('Maximum of '.self::MAX_PLAYERS.' players in Black Jack');
        }

        $this->numOfPlayers = $numOfPlayers;
        $this->deck = new DeckOfCards();
        $this->dealer = new Dealer();
        for ($i = 0; $i < $this->numOfPlayers; ++$i) {
            $this->players[$i] = new Player();
        }

        // Needs to be last or can create error if accessing any BlackJack properties before set
        $this->gameLogic = new GameLogic($this);
    }

    /**
     * drawUpdate.
     */
    private function drawUpdate(int $index): void
    {
        if ($this->players[$index]->isBust()) {
            $this->players[$index]->setGameState(Player::DEALER_WIN);
            $this->players[$index]->resetBet();
            $this->gameLogic->checkIfDealersTurn();

            return;
        }

        if (Player::DOUBLE_DOWN === $this->players[$index]->getGameState()) {
            $this->gameLogic->checkIfDealersTurn();
        }
    }

    /**
     * getNumOfPlayers.
     */
    public function getNumOfPlayers(): int
    {
        return $this->numOfPlayers;
    }

    /**
     * getPlayers.
     *
     * @return array<Player>
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * getDealer.
     */
    public function getDealer(): Dealer
    {
        return $this->dealer;
    }

    /**
     * getDeck.
     */
    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    /**
     * setPlayers.
     *
     * @param array<Player> $players
     */
    public function setPlayers(array $players): void
    {
        // If not to many players
        if (self::MAX_PLAYERS >= count($players)) {
            $this->players = $players;
            $this->numOfPlayers = count($players);
        }
    }

    /**
     * setPlayer.
     */
    public function setPlayer(int $index, Player $player): void
    {
        // If index is out of bounds
        if ($index < 0 or $index >= $this->numOfPlayers) {
            return;
        }

        $this->players[$index] = $player;
    }

    /**
     * setDealer.
     */
    public function setDealer(Dealer $dealer): void
    {
        $this->dealer = $dealer;
    }

    /**
     * setDeck.
     */
    public function setDeck(DeckOfCards $deck): void
    {
        $this->deck = $deck;
    }

    /**
     * isPlayerBust.
     *
     * Returns if the player's hand value is over 21
     */
    public function isPlayerBust(int $index = 0): bool
    {
        // If index is out of bounds
        if ($index < 0 or $index >= $this->numOfPlayers) {
            return true;
        }

        return $this->players[$index]->isBust();
    }

    /**
     * isPlayerBroke.
     *
     * Returns if the player is broke
     */
    public function isPlayerBroke(int $index = 0): bool
    {
        // If index is out of bounds
        if ($index < 0 or $index >= $this->numOfPlayers) {
            return true;
        }

        return $this->players[$index]->isBroke();
    }

    /**
     * isDealerBust.
     *
     * Returns if the dealer's hand value is over 21
     */
    public function isDealerBust(): bool
    {
        return $this->dealer->isBust();
    }

    /**
     * newGame.
     *
     * Sets up a new game
     *
     * @param array<int, int> $bets
     */
    public function newGame(array $bets = []): void
    {
        $this->gameLogic->newGame($bets);
    }

    /**
     * stateOfGame.
     *
     * Returns the current game state.
     *
     * @return array
     *               Descriptive list of array contents:
     *               - numOfPlayers (int)
     *               - playersNames (array<string>)
     *               - playersCards (array<int, array<string>>)
     *               - playersHandValue (array<string>)
     *               - playersCredits (array<string>)
     *               - playersBets (array<string>)
     *               - dealerCards (array<string>)
     *               - dealerHandValue (string)
     *               - gameStates (array<string>)
     *
     * @phpstan-return array{
     *   numOfPlayers: int,
     *   playersNames: array<string>,
     *   playersCards: array<int, array<string>>,
     *   playersHandValue: array<string>,
     *   playersCredits: array<string>,
     *   playersBets: array<string>,
     *   dealerCards: array<string>,
     *   dealerHandValue: string,
     *   gameStates: array<string>
     * }
     */
    public function stateOfGame(): array
    {
        return $this->gameLogic->stateOfGame();
    }

    /**
     * stayPlayer.
     */
    public function stayPlayer(int $index = 0): void
    {
        // If index is out of bounds
        if ($index < 0 or $index >= $this->numOfPlayers) {
            return;
        }

        // If game not over
        if (Player::UNDECIDED === $this->players[$index]->getGameState()) {
            $this->players[$index]->setGameState(Player::STAYED);
            $this->gameLogic->checkIfDealersTurn();
        }
    }

    /**
     * hitPlayer.
     */
    public function hitPlayer(int $index = 0): void
    {
        // If index is out of bounds
        if ($index < 0 or $index >= $this->numOfPlayers) {
            return;
        }

        // Stops drawing new cards if game is over
        if (Player::UNDECIDED === $this->players[$index]->getGameState()) {
            $this->players[$index]->addCard($this->deck->drawCard());

            $this->drawUpdate($index);
        }
    }

    public function doubleDownPlayer(int $index = 0): void
    {
        // If index is out of bounds
        if ($index < 0 or $index >= $this->numOfPlayers) {
            return;
        }

        // Can't double down if game is over
        if (Player::UNDECIDED === $this->players[$index]->getGameState() and 2 === count($this->players[$index]->getString())) {
            // Double bet
            $currentBet = $this->players[$index]->getBet();
            $this->players[$index]->increaseBet($currentBet);

            $this->players[$index]->addCard($this->deck->drawCard());

            $this->players[$index]->setGameState(Player::DOUBLE_DOWN);

            $this->drawUpdate($index);
        }
    }
}
