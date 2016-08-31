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
        $p1 = new Player('Player 1');
        $manager->persist($p1);

        $p2 = new Player('Player 2');
        $manager->persist($p2);

        $game = new Game();
        $game->addPlayer($p1);
        $game->addPlayer($p2);

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
