<?php

namespace App\Cards;

use App\Cards\Card;

class CardHand
{
    private $hand = [];

    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function numberOfCards(): int
    {
        return Count($this->hand);
    }


    public function getValueOfHand(): int
    {
        $value = 0;
        foreach ($this->hand as $card) {
            $value += $card.getValue();
        }
        return $value;
    }

    public function getBlackJackValueOfHand(): int
    {
        $valueHand = 0;
        foreach ($this->hand as $card) {
            $valueCard = $card.getValue();
            if ($valueCard > 10) {
                $valueHand += 10;
            } else {
                $valueHand += $valueCard;
            }
        }
        return $valueHand;
    }

    public function getBlackJackValueOfHandAceHigh(): int
    {
        $valueHand = 0;
        foreach ($this->hand as $card) {
            $valueCard = $card.getValue();
            if ($valueCard == 1) {
                $valueHand += 11;
            } elseif ($valueCard > 10) {
                $valueHand += 10;
            } else {
                $valueHand += $valueCard;
            }
        }
        return $valueHand;
    }

    public function getString(): array
    {
        $cards = [];
        foreach ($this->hand as $card) {
            $cards[] = $card->getString();
        }
        return $cards;
    }
}
