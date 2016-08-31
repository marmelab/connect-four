<?php

namespace Tests\ConnectFour;

use PHPUnit\Framework\TestCase;
use ConnectFour\Game;
use ConnectFour\Board;
use ConnectFour\Player;

class GameTest extends TestCase
{
    private $game;
    private $player1;
    private $player2;

    protected function setUp()
    {
        $this->game = new Game();

        $this->player1 = new Player('First');
        $this->game->addPlayer($this->player1);

        $this->player2 = new Player('Second');
        $this->game->addPlayer($this->player2);
    }

    public function testNumberOfDiscsOnBoard()
    {
        $player = $this->game->getCurrentPlayer();

        $player->dropDisc($this->game, 4);

        $nbDiscs = $this->game->getBoard()->countDiscs();

        $this->assertEquals($nbDiscs, 1);
    }

    /**
     * @expectedException ConnectFour\NotYourTurnException
     */
    public function testCannotDropDiscWhenNotPlayerTurn()
    {
        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $nextPlayer->dropDisc($this->game, 4);
    }

    public function testTurnAlternatesOnDroppingDisc()
    {
        $player = $this->game->getCurrentPlayer();

        $player->dropDisc($this->game, 4);
        $nextPlayer = $this->game->getCurrentPlayer();

        $this->assertNotEquals($player, $nextPlayer);
    }

    /**
     * @expectedException ConnectFour\OutOfBoardException
     */
    public function testAnExceptionIsThrownWhenDiscIsDroppedOutsideTheBoardOnRight()
    {
        $player = $this->game->getCurrentPlayer();

        $player->dropDisc($this->game, Board::COLUMNS + 1);
    }

    /**
     * @expectedException ConnectFour\OutOfBoardException
     */
    public function testAnExceptionIsThrownWhenDiscIsDroppedOutsideTheBoardOnLeft()
    {
        $player = $this->game->getCurrentPlayer();

        $player->dropDisc($this->game, -1);
    }

    public function testPlayerWinsWhenDroppingFourAlignedDiscs()
    {
        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 0);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 3);

        $this->assertEquals($this->game->getWinner(), $player);
    }

    public function testGameIsFinishedWhenOnePlayerWins()
    {
        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 0);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 3);

        $this->assertEquals($this->game->getStatus(), Game::FINISHED);
        $this->assertTrue($this->game->isFinished());
    }

    /**
     * @expectedException ConnectFour\GameFinishedException
     */
    public function testPlayerCannotDropDiscsAnymoreWhenGameIsFinished()
    {
        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 0);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 3);
        // here the first turn wins, game should be over
        $this->assertEquals($this->game->getStatus(), Game::FINISHED);
        $nextPlayer->dropDisc($this->game, 1);
    }

    public function testGameIsFinishedWhenItsDraw()
    {
        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 0);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 0);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 0);
        $nextPlayer->dropDisc($this->game, 1);

        $player->dropDisc($this->game, 2);
        $nextPlayer->dropDisc($this->game, 3);
        $player->dropDisc($this->game, 2);
        $nextPlayer->dropDisc($this->game, 3);
        $player->dropDisc($this->game, 2);
        $nextPlayer->dropDisc($this->game, 3);

        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 5);
        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 5);
        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 5);

        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 0);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 0);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 0);

        $player->dropDisc($this->game, 1);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 1);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 1);
        $nextPlayer->dropDisc($this->game, 2);

        $player->dropDisc($this->game, 3);
        $nextPlayer->dropDisc($this->game, 4);
        $player->dropDisc($this->game, 3);
        $nextPlayer->dropDisc($this->game, 4);
        $player->dropDisc($this->game, 3);
        $nextPlayer->dropDisc($this->game, 4);

        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 6);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 6);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 6);

        $this->assertNull($this->game->getWinner());
        $this->assertTrue($this->game->isFinished());
    }

    /**
     * @expectedException ConnectFour\OutOfBoardException
     */
    public function testPlayerCannotDropDiscsAnymoreWhenColumnIsFull()
    {
        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        for ($i = 0; $i < Board::ROWS + 1; ++$i) {
            if ($i % 2 != 0) {
                $nextPlayer->dropDisc($this->game, 4);
            } else {
                $player->dropDisc($this->game, 4);
            }
        }
    }

    public function testBoardActuallyResets()
    {
        $this->game->getCurrentPlayer()->dropDisc($this->game, 4);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 3);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 5);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 2);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 6);

        $this->assertNotEquals($this->game->getBoard()->countDiscs(), 0);

        $this->game->getBoard()->reset();

        $this->assertEquals($this->game->getBoard()->countDiscs(), 0);
    }

    public function testReplayMovesGetsToSameBoard()
    {
        $this->game->getCurrentPlayer()->dropDisc($this->game, 4);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 3);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 5);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 2);
        $this->game->getCurrentPlayer()->dropDisc($this->game, 6);

        $firstBoard = clone $this->game->getBoard();

        $this->game->getBoard()->reset();
        $this->game->replayMoves();

        $this->assertEquals($firstBoard, $this->game->getBoard());
    }

    public function testGameIsWaitingWhenNotTwoPlayers()
    {
        $game = new Game();

        $player1 = new Player('Lonely');
        $game->addPlayer($player1);

        $this->assertEquals($game->getStatus(), Game::WAITING);
    }

    public function testGameIsPlayingAsSoonAsTheresTwoPlayers()
    {
        $this->assertEquals($this->game->getStatus(), Game::PLAYING);
    }

    public function testGameBoardIsEmptyOnInitialization()
    {
        $this->assertEquals($this->game->getBoard()->countDiscs(), 0);
    }

    public function testDiscCanBeRetrievedAfterBeingDropped()
    {
        $this->game->getCurrentPlayer()->dropDisc($this->game, 4);

        $this->assertNotNull($this->game->getBoard()->getDisc(4, 0));
    }

    public function testThatColorsAreWellAssigned()
    {
        $this->assertEquals($this->game->getPlayerColor($this->player1), Game::FIRST_PLAYER_COLOR);
        $this->assertEquals($this->game->getPlayerColor($this->player2), Game::SECOND_PLAYER_COLOR);
    }
}
