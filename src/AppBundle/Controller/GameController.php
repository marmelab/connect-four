<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ConnectFour\Game;

class GameController extends Controller
{
    /**
     * @Route("/game/{id}", name="viewGame")
     */
    public function viewAction(Game $game)
    {
        $game->replayMoves();

        // TODO : add session check for player nickname

        $view;
        switch ($game->getStatus()) {
            case Game::FINISHED:
                $view = 'finished';
                break;
            case Game::WAITING:
                $view = 'waiting';
                break;
            case Game::PLAYING:
                $view = 'playing';
                break;
            default:
                //TODO : handle errors
        }

        return $this->render("game/$view.html.twig", array(
          'game' => $game,
<<<<<<< 76a6572cba2d4e06410b40999caa21255800ecdb
=======
          'columnBounds' => Board::COLUMNS - 1,
          'rowBounds' => Board::ROWS - 1,
>>>>>>> Fixes board boundaries
      ));
    }

    /**
     * Views a game at its last step.
     *
     * @Route("/game/{gameId}/drop/{col}", name="dropDisc")
     */
    public function dropDiscAction($gameId, $col)
    {
        $gameManager = $this->get('app.game.manager');
        $game = $gameManager->getGame($gameId);

        $player = $game->getCurrentPlayer();
        // TODO : add session check for player nickname

        $player->dropDisc($game, $col);

        $gameManager->saveGame($game);

        return $this->redirectToRoute('viewGame', array(
            'gameId' => $gameId
        ));
    }
}
