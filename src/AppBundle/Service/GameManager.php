<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Exception\GameNotFoundException;

class GameManager
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getGame($gameId)
    {
        $game = $this
          ->entityManager
          ->getRepository("ConnectFour\Game")
          ->findOneById($gameId);

        if (!$game) {
            throw new GameNotFoundException();
        }
        $game->replayMoves();

        return $game;
    }

    public function saveGame($game)
    {
        $this->entityManager->flush();
    }
}
