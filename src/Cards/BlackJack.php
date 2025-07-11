<?php

namespace App\Cards;

use App\Cards\CardHand;
use App\Cards\DeckOfCards;

/**
 * BlackJack
 */
class BlackJack
{
    private DeckOfCards $deck;
    private CardHand $player;
    private CardHand $dealer;
    private int $playerValue;
    private int $dealerValue;
    private bool $dealerHidden;
    private bool $playerBust;
    private bool $dealerBust;
    private bool $gameOver;
    // -1 = Undecided, 0 = Tie, 1 = Player, 2 = Dealer
    private int $winner;

    /**
     * __construct
     *
     * Constructor of the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->player = new CardHand();
        $this->dealer = new CardHand();

        $this->deck->shuffleDeck();

        $this->setupGame();
    }

    /**
     * setupGame
     *
     * Sets up a game of Black Jack by dealing out the cards and setting upp the variables
     *
     * @return void
     */
    private function setupGame(): void
    {
        for ($i = 0; $i < 2; $i++) {
            $this->player->addCard($this->deck->drawCard());
            $this->dealer->addCard($this->deck->drawCard());
        }

        $this->playerValue = $this->player->getBlackJackValueAceHigh();
        $this->dealerValue = $this->dealer->getBlackJackValueAceHigh();

        $this->dealerHidden = true;
        $this->playerBust = false;
        $this->dealerBust = false;
        $this->gameOver = false;
        $this->winner = -1;
    }

    /**
     * calculateWinner
     *
     * Calculates the winner of the game
     *
     * @return void
     */
    private function calculateWinner(): void
    {
        // -1 = Undecided, 0 = Tie, 1 = Player, 2 = Dealer
        if ($this->playerBust) {
            $this->winner = 2;
            return;
        }

        if ($this->dealerBust) {
            $this->winner = 1;
            return;
        }

        if ($this->dealerValue > $this->playerValue) {
            $this->winner = 2;
        } elseif ($this->dealerValue == $this->playerValue) {
            $this->winner = 0;
        } elseif ($this->dealerValue < $this->playerValue) {
            $this->winner = 1;
        }
    }


    /**
     * playDealer
     *
     * The logic for the dealer to play
     *
     * @return void
     */
    private function playDealer(): void
    {
        while ($this->dealerValue < 17) {
            $this->dealer->addCard($this->deck->drawCard());

            $lowValue = $this->dealer->getBlackJackValue();
            $highValue = $this->dealer->getBlackJackValueAceHigh();

            if ($lowValue > 21) {
                $this->dealerBust = true;
            }

            $this->dealerValue = ($highValue > 21) ? $lowValue : $highValue;
        }

        $this->gameOver = true;
    }

    /**
     * stateOfGame
     *
     * Returns a mixed array containing the current state of the game
     *
     * @return array<mixed>
     */
    public function stateOfGame(): array
    {
        $data = [
            "player" => $this->player->getString(),
            "playerValue" => strval($this->playerValue),
            "dealer" => $this->dealer->getString(),
            "dealerValue" => strval($this->dealerValue),
            "winner" => "Undecided",
        ];

        if ($this->dealerHidden) {
            $data["dealer"] = [$this->dealer->getString()[0], "🂠"];
            $data["dealerValue"] = "0";
        }

        if ($this->gameOver) {
            $this->calculateWinner();
            switch ($this->winner) {
                case 0:
                    $data["winner"] = "Tie";
                    break;
                case 1:
                    $data["winner"] = "Player";
                    break;
                case 2:
                    $data["winner"] = "Dealer";
                    break;
            }
        }

        return $data;
    }

    /**
     * isPlayerBust
     *
     * Returns if the player's hand value is over 21
     *
     * @return bool
     */
    public function isPlayerBust(): bool
    {
        return $this->playerBust;
    }

    /**
     * isDealerBust
     *
     * Returns if the dealer's hand value is over 21
     *
     * @return bool
     */
    public function isDealerBust(): bool
    {
        return $this->dealerBust;
    }

    /**
     * resetGame
     *
     * Resets the game to a new game
     *
     * @return void
     */
    public function resetGame(): void
    {
        $this->player = new CardHand();
        $this->dealer = new CardHand();

        $this->deck->reshuffleDeck();

        $this->setupGame();
    }

    /**
     * hitPlayer
     *
     * The player draws a card and we check if the player is bust and update the hand value
     *
     * @return void
     */
    public function hitPlayer(): void
    {
        //Stops drawing new cards if game is over
        if ($this->gameOver == false) {
            $this->player->addCard($this->deck->drawCard());

            $lowValue = $this->player->getBlackJackValue();
            $highValue = $this->player->getBlackJackValueAceHigh();

            if ($lowValue > 21) {
                $this->gameOver = true;
                $this->playerBust = true;
                $this->dealerHidden = false;
            }

            $this->playerValue = ($highValue > 21) ? $lowValue : $highValue;
        }
    }

    /**
     * stayPlayer
     *
     * The player stay and we call upon the dealer to play
     *
     * @return void
     */
    public function stayPlayer(): void
    {
        //Stops dealer doing anything if game is over
        if ($this->gameOver == false) {
            $this->dealerHidden = false;
            $this->playDealer();
        }
    }
}
