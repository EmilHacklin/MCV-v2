<?php

namespace App\Cards;

/**
 * CardHand.
 */
class CardHand
{
    /**
     * @var array<Card> Is an array that contains the Card objects
     */
    protected array $hand = [];

    /**
     * addCard.
     *
     * Add a card to the hand array
     */
    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * cardCount.
     *
     * Returns the number of cards in hand
     */
    public function cardCount(): int
    {
        return count($this->hand);
    }

    /**
     * getValueOfHand'.
     *
     * Return the value of the hand
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
     * getValueOfHandAceHigh.
     *
     * Return the value of the hand if the Ace cards have a high value (14)
     */
    public function getValueAceHigh(): int
    {
        $valueHand = 0;
        foreach ($this->hand as $card) {
            $valueCard = $card->getValue();
            $valueHand += (1 == $valueCard) ? 14 : $valueCard;
        }

        return $valueHand;
    }

    /**
     * getBlackJackValueOfHand.
     *
     * Return the Black Jack value of the hand (J,Q,K = 10)
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
     * getBlackJackValueOfHandAceHigh.
     *
     * Return the Black Jack value of the hand (J,Q,K = 10) if the Ace cards have a high value (11)
     */
    public function getBlackJackValueAceHigh(): int
    {
        $valueHand = 0;
        foreach ($this->hand as $card) {
            $valueCard = $card->getValue();
            $valueHand += (1 == $valueCard) ? 11 :
             (($valueCard > 10) ? 10 : $valueCard);
        }

        return $valueHand;
    }

    /**
     * getString.
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
