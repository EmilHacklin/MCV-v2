<?php

namespace App\Game\BlackJack;

use App\Cards\Card;
use App\Cards\CardHand;

/**
 * Player.
 */
class Player extends CardHand
{
    protected int $handValue;
    protected bool $isBust;

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
     * isBust.
     */
    public function isBust(): bool
    {
        return $this->isBust;
    }

    /**
     * getHandValue.
     */
    public function getHandValue(): int
    {
        return $this->handValue;
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
