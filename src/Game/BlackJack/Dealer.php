<?php

namespace App\Game\BlackJack;

/**
 * Dealer.
 */
class Dealer extends Player
{
    /**
     * play.
     *
     * Simple logic to play the dealer
     */
    public function play(): bool
    {
        // If hand value 17 or higher
        if ($this->handValue > 16) {
            // Stop playing
            return false;
        }

        // Continue playing
        return true;
    }
}
