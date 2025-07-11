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
    * Is an array that contains the Card objects
    */
    private array $hand = [];

    /**
     * addCard
     *
     * Add a card to the hand array
     *
     * @param  Card $card
     * @return void
     */
    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * cardCount
     *
     * Returns the number of cards in hand
     *
     * @return int
     */
    public function cardCount(): int
    {
        return Count($this->hand);
    }


    /**
     * getValueOfHand'
     *
     * Return the value of the hand
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
     * Return the value of the hand if the Ace cards have a high value (14)
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
     * Return the Black Jack value of the hand (J,Q,K = 10)
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
     * Return the Black Jack value of the hand (J,Q,K = 10) if the Ace cards have a high value (11)
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
     * Return the hand as an array of strings
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
