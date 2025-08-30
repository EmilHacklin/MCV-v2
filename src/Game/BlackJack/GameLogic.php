<?php

namespace App\Game\BlackJack;

use App\Game\BlackJack;

class GameLogic
{
    private BlackJack $blackjack;
    private bool $isDealersTurn;

    public function __construct(BlackJack $blackjack)
    {
        $this->blackjack = $blackjack;
        $this->isDealersTurn = false;
    }

    /**
     * calculateWinner.
     */
    private function calculateWinner(int $index): void
    {
        // Get variables
        $player = $this->blackjack->getPlayers()[$index];
        $credits = $player->getCredits();
        $bet = $player->getBet();
        $gameState = $player->getGameState();
        $dealer = $this->blackjack->getDealer();

        // The winner is already decided or player broke
        if (Player::DEALER_WIN === $gameState or true === $player->isBroke()) {
            return;
        }

        // If player is busted dealer wins
        if (true === $player->isBust()) {
            $player->setGameState(Player::DEALER_WIN);
            $player->resetBet();
            $this->blackjack->setPlayer($index, $player);

            return;
        }

        // If dealer is bust: player wins
        if (true === $dealer->isBust()) {
            $player->setGameState(Player::PLAYER_WIN);
            $player->setCredits($credits + ($bet * 2));
            $player->resetBet();
            $this->blackjack->setPlayer($index, $player);

            return;
        }

        // Both are not busts, compare their hand values
        $playerHandValue = $player->getHandValue();
        $dealerHandValue = $dealer->getHandValue();

        if ($playerHandValue > $dealerHandValue) {
            $player->setGameState(Player::PLAYER_WIN);
            $player->setCredits($credits + ($bet * 2));
            $player->resetBet();
            $this->blackjack->setPlayer($index, $player);

            return;
        }

        if ($playerHandValue < $dealerHandValue) {
            $player->setGameState(Player::DEALER_WIN);
            $player->resetBet();
            $this->blackjack->setPlayer($index, $player);

            return;
        }

        $player->setGameState(Player::TIE);
        $player->setCredits($credits + $bet);
        $player->resetBet();
        $this->blackjack->setPlayer($index, $player);
    }

    /**
     * calculateAllWinners.
     */
    private function calculateAllWinners(): void
    {
        $numOfPlayers = $this->blackjack->getNumOfPlayers();
        for ($i = 0; $i < $numOfPlayers; ++$i) {
            $this->calculateWinner($i);
        }
    }

    /**
     * playDealer.
     *
     * The logic for the dealer to play
     */
    private function playDealer(): void
    {
        // Get variables
        $dealer = $this->blackjack->getDealer();
        $deck = $this->blackjack->getDeck();

        while (true === $dealer->play()) {
            $dealer->addCard($deck->drawCard());
        }

        // Set variables
        $this->blackjack->setDealer($dealer);
        $this->blackjack->setDeck($deck);

        // Calculate all winners
        $this->calculateAllWinners();
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
        // Get variables
        $players = $this->blackjack->getPlayers();
        $dealer = $this->blackjack->getDealer();
        $deck = $this->blackjack->getDeck();
        $numOfPlayers = $this->blackjack->getNumOfPlayers();

        // Reset game
        $this->isDealersTurn = false;
        $dealer->dropHand();
        $deck->reshuffleDeck();

        // Set bets
        for ($i = 0; $i < $numOfPlayers; ++$i) {
            // Reset the player
            $players[$i]->resetBet();
            $players[$i]->dropHand();
            $players[$i]->setGameState(Player::UNDECIDED);

            // If the player is broke skip
            if (true === $players[$i]->isBroke()) {
                continue;
            }

            // If there is a bet and it is lager then minimum
            (true === array_key_exists($i, $bets) and BlackJack::MINIMUM_BET < $bets[$i]) ? $players[$i]->increaseBet($bets[$i]) : $players[$i]->increaseBet(BlackJack::MINIMUM_BET);
        }

        // For 2 rounds of dealing a card
        for ($round = 0; $round < 2; ++$round) {
            for ($i = 0; $i < $numOfPlayers; ++$i) {
                // If the player is broke skip
                if (true === $players[$i]->isBroke()) {
                    continue;
                }

                $players[$i]->addCard($deck->drawCard());
            }
            $dealer->addCard($deck->drawCard());
        }

        // Set variables
        $this->blackjack->setPlayers($players);
        $this->blackjack->setDealer($dealer);
        $this->blackjack->setDeck($deck);
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
        // Get variables
        $players = $this->blackjack->getPlayers();
        $dealer = $this->blackjack->getDealer();
        $numOfPlayers = $this->blackjack->getNumOfPlayers();

        // Map Player game states to strings
        $gameStateMap = [
            Player::DOUBLE_DOWN => 'Double Down',
            Player::STAYED => 'Stayed',
            Player::UNDECIDED => 'Undecided',
            Player::TIE => 'Tie',
            Player::PLAYER_WIN => 'Player',
            Player::DEALER_WIN => 'Dealer',
        ];

        $data = [
            'numOfPlayers' => $numOfPlayers,
            'playersNames' => [],
            'playersCards' => [],
            'playersHandValue' => [],
            'playersCredits' => [],
            'playersBets' => [],
            'dealerCards' => $dealer->getString(),
            'dealerHandValue' => strval($dealer->getHandValue()),
            'gameStates' => [],
        ];

        for ($i = 0; $i < $numOfPlayers; ++$i) {
            $data['playersNames'][$i] = $players[$i]->getName();
            $data['playersCards'][$i] = $players[$i]->getString();
            $data['playersHandValue'][$i] = strval($players[$i]->getHandValue());
            $data['playersCredits'][$i] = strval($players[$i]->getCredits());
            $data['playersBets'][$i] = strval($players[$i]->getBet());

            $gameState = $players[$i]->getGameState();
            // Use the mapping array to get the string
            $data['gameStates'][$i] = $gameStateMap[$gameState] ?? 'Unknown';
        }

        // If all players are not done
        if (false === $this->isDealersTurn) {
            $dealerCards = $dealer->getString();
            if (0 !== count($dealerCards)) {
                $data['dealerCards'] = [$dealerCards[0], '🂠'];
                $data['dealerHandValue'] = '0';
            }
        }

        return $data;
    }

    /**
     * checkIfDealersTurn.
     */
    public function checkIfDealersTurn(): void
    {
        // Get variables
        $players = $this->blackjack->getPlayers();
        $numOfPlayers = $this->blackjack->getNumOfPlayers();

        // Check if all players are done
        $allPlayersDone = true;

        for ($i = 0; $i < $numOfPlayers; ++$i) {
            $roundState = $players[$i]->getGameState();
            // If undecided and not broke
            if (Player::UNDECIDED === $roundState and false === $players[$i]->isBroke()) {
                $allPlayersDone = false;
                break;
            }
        }

        if (true === $allPlayersDone) {
            $this->isDealersTurn = true;
            $this->playDealer();
        }
    }
}
