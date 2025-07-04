<?php

namespace App\Cards;

use App\Cards\Card;

/**
 * CardHand
 */
class CardHand
{
    /**
    * @var array<Card> $hand
    */
    private array $hand = [];

    /**
     * addCard
     *
     * @param  Card $card
     * @return void
     */
    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * numberOfCards
     *
     * @return int
     */
    public function numberOfCards(): int
    {
        return Count($this->hand);
    }


    /**
     * getValueOfHand
     *
     * @return int
     */
    public function getValue(): int
    {
        $value = 0;
        foreach ($this->hand as $card) {
            $value += $card->getValue();
        }
        return $value;
    }

    /**
     * getValueOfHandAceHigh
     *
     * @return int
     */
    public function getValueAceHigh(): int
    {
        $valueHand = 0;
        foreach ($this->hand as $card) {
            $valueCard = $card->getValue();
            $valueHand += ($valueCard == 1) ? 14 : $valueCard;
        }
        return $valueHand;
    }

    /**
     * getBlackJackValueOfHand
     *
     * @return int
     */
    public function getBlackJackValue(): int
    {
        $valueHand = 0;
        foreach ($this->hand as $card) {
            $valueCard = $card->getValue();
            $valueHand += ($valueCard > 10) ? 10 : $valueCard;
        }
        return $valueHand;
    }

    /**
     * getBlackJackValueOfHandAceHigh
     *
     * @return int
     */
    public function getBlackJackValueAceHigh(): int
    {
        $valueHand = 0;
        foreach ($this->hand as $card) {
            $valueCard = $card->getValue();
            $valueHand += ($valueCard == 1) ? 11 :
             (($valueCard > 10) ? 10 : $valueCard);
        }
        return $valueHand;
    }

    /**
     * getString
     *
     * @return array<string>
     */
    public function getString(): array
    {
        $cards = [];
        foreach ($this->hand as $card) {
            $cards[] = $card->getString();
        }
        return $cards;
    }
}
