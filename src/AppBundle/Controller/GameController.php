<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ConnectFour\Board;

class GameController extends Controller
{
    /**
     * Views a game at its last step.
     *
     * @Route("/game/{gameId}", name="viewGame")
     */
    public function viewAction($gameId)
    {
        $gameManager = $this->get('app.game.manager');
        $game = $gameManager->getGame($gameId);

        // TODO : add session check for player nickname
        $status = $game->getStatus();

        return $this->render("game/$status.html.twig", array(
          'game' => $game,
          'columnBound' => Board::COLUMNS,
          'rowBound' => Board::ROWS,
      ));
    }
}
