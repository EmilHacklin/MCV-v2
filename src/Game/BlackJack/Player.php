<?php

namespace App\Game\BlackJack;

use App\Cards\Card;
use App\Cards\CardHand;

/**
 * Player.
 */
class Player extends CardHand
{
    public const DEFAULT_PLAYER_NAME = 'Player';
    public const DEFAULT_STARTING_CREDITS = 1000;
    // Game states
    public const DOUBLE_DOWN = -3;
    public const STAYED = -2;
    public const UNDECIDED = -1;
    // Outcomes
    public const TIE = 0;
    public const PLAYER_WIN = 1;
    public const DEALER_WIN = 2;

    protected string $name;
    protected int $credits;
    protected int $bet;
    protected int $handValue;
    protected int $gameState;
    protected bool $isBust;

    /**
     * __construct.
     *
     * @return void
     */
    public function __construct(string $name = self::DEFAULT_PLAYER_NAME, int $credits = self::DEFAULT_STARTING_CREDITS)
    {
        $this->setName($name);

        $this->setCredits($credits);

        $this->bet = 0;

        $this->handValue = 0;

        $this->gameState = self::UNDECIDED;
    }

    /**
     * dropHand.
     */
    public function dropHand(): void
    {
        $this->hand = [];
        $this->handValue = 0;
    }

    /**
     * checkIsBust.
     */
    protected function checkIsBust(): void
    {
        ($this->handValue > 21) ? $this->isBust = true : $this->isBust = false;
    }

    /**
     * calculateHandValue.
     */
    protected function calculateHandValue(): void
    {
        $lowValue = $this->getBlackJackValue();
        $highValue = $this->getBlackJackValueAceHigh();

        $this->handValue = ($highValue > 21) ? $lowValue : $highValue;

        $this->checkIsBust();
    }

    /**
     * getName.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * setName.
     */
    public function setName(string $name): void
    {
        $this->name = htmlspecialchars($name);
    }

    /**
     * getCredits.
     */
    public function getCredits(): int
    {
        return $this->credits;
    }

    /**
     * setCredits.
     */
    public function setCredits(int $credits): void
    {
        if ($credits < 0) {
            $this->credits = 0;

            return;
        }

        $this->credits = $credits;
    }

    /**
     * getBet.
     */
    public function getBet(): int
    {
        return $this->bet;
    }

    /**
     * increaseBet.
     */
    public function increaseBet(int $betIncrease): void
    {
        if ($betIncrease > 0) {
            // If you are betting more then the credits left
            if ($betIncrease > $this->credits) {
                $betIncrease = $this->credits;
            }

            $this->bet += $betIncrease;
            $this->credits -= $betIncrease;
        }
    }

    /**
     * resetBet.
     */
    public function resetBet(): void
    {
        $this->bet = 0;
    }

    /**
     * changeCredits.
     */
    public function changeCredits(int $change): void
    {
        // Need to cast to int or else the balance can become a float
        $balance = (int) ($this->credits + $change);

        if ($balance < 0) {
            // If integer overflow happen
            if ($change > 0) {
                $this->credits = PHP_INT_MAX;

                return;
            }

            $this->credits = 0;

            return;
        }

        $this->credits = $balance;
    }

    /**
     * isBust.
     */
    public function isBust(): bool
    {
        return $this->isBust;
    }

    public function isBroke(): bool
    {
        return (0 === $this->credits and 0 === $this->bet) ? true : false;
    }

    /**
     * getHandValue.
     */
    public function getHandValue(): int
    {
        return $this->handValue;
    }

    /**
     * getGameState.
     */
    public function getGameState(): int
    {
        return $this->gameState;
    }

    /**
     * setGameState.
     */
    public function setGameState(int $gameState): void
    {
        // Map Player game states
        $gameStateMap = [
            Player::DOUBLE_DOWN => Player::DOUBLE_DOWN,
            Player::STAYED => Player::STAYED,
            Player::UNDECIDED => Player::UNDECIDED,
            Player::TIE => Player::TIE,
            Player::PLAYER_WIN => Player::PLAYER_WIN,
            Player::DEALER_WIN => Player::DEALER_WIN,
        ];

        $this->gameState = $gameStateMap[$gameState] ?? 404;
    }

    /**
     * addCard.
     */
    public function addCard(Card $card): void
    {
        parent::addCard($card);

        $this->calculateHandValue();
    }
}
