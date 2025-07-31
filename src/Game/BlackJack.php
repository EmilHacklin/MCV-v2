<?php

namespace App\Game;

use App\Cards\DeckOfCards;
use App\Game\BlackJack\Dealer;
use App\Game\BlackJack\Player;

/**
 * BlackJack.
 */
class BlackJack
{
    public const MAX_PLAYERS = 7;
    private int $numOfPlayers;
    private DeckOfCards $deck;
    private Dealer $dealer;
    /**
     * @var array<Player> Is an array that contains BlackJackPlayer objects
     */
    private array $players;
    /**
     * -2 = Stayed, -1 = Undecided, 0 = Tie, 1 = Player, 2 = Dealer.
     *
     * @var array<int> Is an array that contains the outcomes of the players games
     */
    private array $gameStates;
    private bool $dealersTurn;

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
            $this->gameStates[$i] = -1;
        }

        $this->deck->shuffleDeck();

        $this->setupGame();
    }

    /**
     * setupGame.
     *
     * Sets up a game of Black Jack by dealing out the cards and setting upp the variables
     */
    private function setupGame(): void
    {
        // For 2 rounds of dealing a card
        for ($round = 0; $round < 2; ++$round) {
            for ($i = 0; $i < $this->numOfPlayers; ++$i) {
                $this->players[$i]->addCard($this->deck->drawCard());
            }
            $this->dealer->addCard($this->deck->drawCard());
        }

        for ($i = 0; $i < $this->numOfPlayers; ++$i) {
            $this->gameStates[$i] = -1;
        }

        $this->dealersTurn = false;
    }

    /**
     * calculateWinner.
     */
    private function calculateWinner(int $index): void
    {
        // Stayed = -2, -1 = Undecided, 0 = Tie, 1 = Player, 2 = Dealer

        // The winner is already decided
        if (2 === $this->gameStates[$index]) {
            $this->gameStates[$index] = 2;

            return;
        }

        // If dealer is bust: player wins
        if (true === $this->dealer->isBust()) {
            $this->gameStates[$index] = 1;

            return;
        }

        $playerHandValue = $this->players[$index]->getHandValue();
        $dealerHandValue = $this->dealer->getHandValue();

        // Both are not busts, compare their hand values
        if ($playerHandValue > $dealerHandValue) {
            $this->gameStates[$index] = 1; // player wins

            return; // Exit early after setting result
        }

        if ($playerHandValue < $dealerHandValue) {
            $this->gameStates[$index] = 2; // dealer wins

            return; // Exit early after setting result
        }

        // If neither of the above conditions matched, hand values are equal
        $this->gameStates[$index] = 0; // tie
    }

    /**
     * playDealer.
     *
     * The logic for the dealer to play
     */
    private function playDealer(): void
    {
        while (true === $this->dealer->play()) {
            $this->dealer->addCard($this->deck->drawCard());
        }

        // Update the state of game
        $this->stateOfGame();
    }

    /**
     * checkIfDealersTurn.
     */
    private function checkIfDealersTurn(): void
    {
        // Check if all players are done
        $allPlayersDone = !in_array(-1, $this->gameStates, true);

        if (true === $allPlayersDone) {
            $this->dealersTurn = true;
            $this->playDealer();
        }
    }

    /**
     * stateOfGame.
     *
     * Returns the current game state.
     *
     * @return array
     *               Descriptive list of array contents:
     *               - numOfPlayers (string)
     *               - playersCards (array<int, array<string>>)
     *               - playersHandValue (array<string>)
     *               - dealerCards (array<string>)
     *               - dealerHandValue (string)
     *               - gameStates (array<string>)
     *
     * @phpstan-return array{
     *   numOfPlayers: string,
     *   playersCards: array<int, array<string>>,
     *   playersHandValue: array<string>,
     *   dealerCards: array<string>,
     *   dealerHandValue: string,
     *   gameStates: array<string>
     * }
     */
    public function stateOfGame(): array
    {
        $data = [
            'numOfPlayers' => strval($this->numOfPlayers),
            'playersCards' => [],
            'playersHandValue' => [],
            'dealerCards' => $this->dealer->getString(),
            'dealerHandValue' => strval($this->dealer->getHandValue()),
            'gameStates' => [],
        ];

        for ($i = 0; $i < $this->numOfPlayers; ++$i) {
            $data['playersCards'][$i] = $this->players[$i]->getString();
            $data['playersHandValue'][$i] = strval($this->players[$i]->getHandValue());

            // If all players are done
            if (true === $this->dealersTurn) {
                $this->calculateWinner($i);
            }

            switch ($this->gameStates[$i]) {
                case -1:
                    $data['gameStates'][$i] = 'Undecided';
                    break;
                case 0:
                    $data['gameStates'][$i] = 'Tie';
                    break;
                case 1:
                    $data['gameStates'][$i] = 'Player';
                    break;
                case 2:
                    $data['gameStates'][$i] = 'Dealer';
                    break;
            }
        }

        // If all players are not done
        if (false === $this->dealersTurn) {
            $data['dealerCards'] = [$this->dealer->getString()[0], '🂠'];
            $data['dealerHandValue'] = '0';
        }

        return $data;
    }

    /**
     * isPlayerBust.
     *
     * Returns if the player's hand value is over 21
     */
    public function isPlayerBust(int $index = 0): bool
    {
        return $this->players[$index]->isBust();
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
     * resetGame.
     *
     * Resets the game to a new game
     */
    public function resetGame(): void
    {
        $this->dealer = new Dealer();
        $this->players = [];
        for ($i = 0; $i < $this->numOfPlayers; ++$i) {
            $this->players[] = new Player();
        }

        $this->deck->reshuffleDeck();

        $this->setupGame();
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
        if (-1 === $this->gameStates[$index]) {
            $this->players[$index]->addCard($this->deck->drawCard());

            if ($this->players[$index]->isBust()) {
                $this->gameStates[$index] = 2;
                $this->checkIfDealersTurn();
            }
        }
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
        if (-1 === $this->gameStates[$index]) {
            $this->gameStates[$index] = -2;
            $this->checkIfDealersTurn();
        }
    }
}
