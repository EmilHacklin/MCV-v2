<?php

namespace App\Tests\Cards;

use PHPUnit\Framework\TestCase;
use App\Cards\CardGraphic;
use App\Cards\DeckOfCards;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * testCreateObject
     *
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     *
     * @return void
     */
    public function testCreateObject(): void
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf(DeckOfCards::class, $deck);

        $res = $deck->getString();
        $this->assertNotEmpty($res);
    }

    /**
     * testDeckManipulation
     *
     * Test the various ways to manipulate the deck
     *
     * @return void
     */
    public function testDeckManipulation(): void
    {
        $deck = new DeckOfCards();
        $shuffledDeck = new DeckOfCards();

        $shuffledDeck->shuffleDeck();
        $this->assertNotEquals($deck->getString(), $shuffledDeck->getString());

        $shuffledDeck->resetDeck();
        $this->assertEquals($deck->getString(), $shuffledDeck->getString());
    }

    /**
     * testDrawCard
     *
     * Test the draw card function and then the reshuffle function
     *
     * @return void
     */
    public function testDrawCard(): void
    {
        $deck = new DeckOfCards();

        $cardCount = $deck->cardCount();
        $this->assertEquals(52, $cardCount);

        $card = $deck->drawCard();
        $this->assertEquals('🂱', $card->getString());
        $cardCount = $deck->cardCount();
        $this->assertEquals(51, $cardCount);

        for ($i = 0; $i < $cardCount; $i++) {
            $deck->drawCard();
        }

        $card = $deck->drawCard();
        $this->assertEquals(CardGraphic::BLANK_CARD, $card->getString());

        $deck->reshuffleDeck();
        $cardCount = $deck->cardCount();
        $this->assertEquals(52, $cardCount);

        $deckReference = new DeckOfCards();
        $this->assertNotEquals($deckReference->getString(), $deck->getString());
    }
}
