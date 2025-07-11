<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;

class DiceGameController extends AbstractController
{
    #[Route("/game/pig", name: "pig_start")]
    public function home(): Response
    {
        return $this->render('pig/home.html.twig');
    }

    #[Route("/game/pig/init", name: "pig_init_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('pig/init.html.twig');
    }

    #[Route("/game/pig/init", name: "pig_init_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numDice = $request->request->get('num_dices');

        /** @var DiceHand $hand */
        $hand = new DiceHand();
        for ($i = 1; $i <= $numDice; $i++) {
            $hand->addDie(new DiceGraphic());
        }
        $hand->roll();

        $session->set("pig_diceHand", $hand);
        $session->set("pig_dices", $numDice);
        $session->set("pig_round", 0);
        $session->set("pig_total", 0);

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/play", name: "pig_play", methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        /** @var DiceHand $hand */
        $hand = $session->get("pig_diceHand");

        $data = [
            "pigDices" => $session->get("pig_dices"),
            "pigRound" => $session->get("pig_round"),
            "pigTotal" => $session->get("pig_total"),
            "diceValues" => $hand->getString()
        ];

        return $this->render('pig/play.html.twig', $data);
    }

    #[Route("/game/pig/roll", name: "pig_roll", methods: ['POST'])]
    public function roll(
        SessionInterface $session
    ): Response {
        /** @var DiceHand $hand */
        $hand = $session->get("pig_diceHand");

        $hand->roll();

        /** @var int $ roundTotal*/
        $roundTotal = $session->get("pig_round");
        $round = 0;

        $values = $hand->getValues();
        foreach ($values as $value) {
            if ($value === 1) {
                $round = 0;
                $roundTotal = 0;

                $this->addFlash(
                    'warning',
                    'You got a 1 and you lost the round points!'
                );

                break;
            }
            $round += $value;
        }

        $session->set("pig_round", $roundTotal + $round);

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/save", name: "pig_save", methods: ['POST'])]
    public function save(
        SessionInterface $session
    ): Response {
        /** @var int $ roundTotal*/
        $roundTotal = $session->get("pig_round");
        /** @var int $ gameTotal*/
        $gameTotal = $session->get("pig_total");

        $session->set("pig_round", 0);

        $session->set("pig_total", $roundTotal + $gameTotal);

        $this->addFlash(
            'notice',
            'Your round was saved to the total!'
        );

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/test/roll", name: "test_roll_dice")]
    public function testRollDice(): Response
    {
        //$die = new Dice();
        $die = new DiceGraphic();

        $data = [
            "dice" => $die->roll(),
            "diceString" => $die->getString()
        ];

        return $this->render('pig/test/roll.html.twig', $data);
    }

    #[Route("/game/pig/test/roll/{num<\d+>}", name: "test_roll_num_dices")]
    public function testRollDices(int $num): Response
    {
        if ($num > 99) {
            throw new Exception("Can't roll more than 99 dices!");
        }
        if ($num < 1) {
            throw new Exception("Can't roll less than 1 die!");
        }

        $diceRolls = [];
        for ($i = 0; $i < $num; $i++) {
            //$die = new Dice();
            $die = new DiceGraphic();
            $die->roll();
            $diceRolls[] = $die->getString();
        };

        $data = [
            "num_dices" => count($diceRolls),
            "diceRolls" => $diceRolls,
        ];

        return $this->render('pig/test/roll_many.html.twig', $data);
    }

    #[Route("/game/pig/test/dicehand/{num<\d+>}", name: "test_dicehand")]
    public function testDiceHand(int $num): Response
    {
        if ($num > 99) {
            throw new Exception("Can't roll more than 99 dices!");
        }
        if ($num < 1) {
            throw new Exception("Can't roll less than 1 die!");
        }

        $hand = new DiceHand();

        for ($i = 1; $i <= $num; $i++) {
            ($i % 2 === 1) ? $hand->addDie(new DiceGraphic()) : $hand->addDie(new Dice());
        }

        $hand->roll();

        $data = [
            "num_dices" => $hand->getNumberDices(),
            "diceRoll" => $hand->getString(),
        ];

        return $this->render('pig/test/dicehand.html.twig', $data);
    }
}
