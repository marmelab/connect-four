<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ConnectFour\Game;
use ConnectFour\Board;
use ConnectFour\GameFinishedException;
use ConnectFour\NotYourTurnException;
use ConnectFour\OutOfBoardException;

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
      ));
    }

    /**
     * Views a game at its last step.
     *
     * @Route("/game/{id}/drop/{column}", name="dropDisc")
     */
    public function dropDiscAction(Game $game, int $column)
    {
        $game->replayMoves();

        $player = $game->getCurrentPlayer();
        // TODO : add session check for player nickname

        try {
            $player->dropDisc($game, $column);
        } catch (GameFinishedException $gfe) {
            $this->addFlash(
                'notice',
                'Game is finished, you cannot play anymore.'
            );
        } catch (NotYourTurnException $nyte) {
            $this->addFlash(
                'notice',
                'It\'s your opponent\'s turn.'
            );
        } catch (OutOfBoardException $oobe) {
            $this->addFlash(
                'notice',
                'Cannot play outside of game board.'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'notice',
                sprintf('Something happened : %s', $e->getMessage())
            );
        }

        $this->getDoctrine()
            ->getManager()
            ->flush();

        return $this->redirectToRoute('viewGame', array(
            'id' => $game->getId(),
        ));
    }
}
