<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ConnectFour\Player;
use ConnectFour\Game;

class LoadGames implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $firstPlayer = new Player('Player 1');
        $manager->persist($firstPlayer);

        $secondPlayer = new Player('Player 2');
        $manager->persist($secondPlayer);

        $game = new Game();
        $game->addPlayer($firstPlayer);
        $game->addPlayer($secondPlayer);

        $game->getCurrentPlayer()->dropDisc($game, 4);
        $game->getCurrentPlayer()->dropDisc($game, 5);
        $game->getCurrentPlayer()->dropDisc($game, 4);
        $game->getCurrentPlayer()->dropDisc($game, 5);
        $game->getCurrentPlayer()->dropDisc($game, 6);
        $game->getCurrentPlayer()->dropDisc($game, 5);
        $game->getCurrentPlayer()->dropDisc($game, 4);
        $game->getCurrentPlayer()->dropDisc($game, 6);
        $game->getCurrentPlayer()->dropDisc($game, 4);
        $game->getCurrentPlayer()->dropDisc($game, 2);
        $game->getCurrentPlayer()->dropDisc($game, 4);
        $game->getCurrentPlayer()->dropDisc($game, 5);

        $manager->persist($game);

        $manager->flush();
    }
}
