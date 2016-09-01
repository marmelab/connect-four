<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ConnectFour\Board;
use ConnectFour\Game;

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

        $view;
        switch($game->getStatus())
        {
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
          'game' => $game
      ));
    }
}
