<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ConnectFour\Game;

class GameController extends Controller
{
    /**
     * @Route("/game/{gameId}", name="viewGame")
     */
    public function viewAction($gameId)
    {
        $game = $this->getDoctrine()
            ->getRepository("ConnectFour\Game")
            ->findOneById($gameId);

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
      ));
    }
}
