<?php

namespace Tests\ConnectFour;

use PHPUnit\Framework\TestCase;
use ConnectFour\Game;
use ConnectFour\Board;
use ConnectFour\Player;

class GameTest extends TestCase
{
    protected $game;
    protected $player1;
    protected $player2;

    protected function setUp()
    {
        $this->game = new Game();

        $this->player1 = new Player("First");
        $this->game->addPlayer($this->player1);

        $this->player2 = new Player("Second");
        $this->game->addPlayer($this->player2);
    }

    public function testNumberOfDiscsOnBoard()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

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
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 4);
    }

    public function testTurnAlternatesOnDroppingDisc()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        
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
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $player = $this->game->getCurrentPlayer();

        $player->dropDisc($this->game, Board::COLUMNS + 1);
    }

    /**
     * @expectedException ConnectFour\OutOfBoardException
     */
    public function testAnExceptionIsThrownWhenDiscIsDroppedOutsideTheBoardOnLeft()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $player = $this->game->getCurrentPlayer();

        $player->dropDisc($this->game, 0);
    }

    public function testPlayerWinsWhenDropingFourAlignedDiscs()
    {
        $this->markTestIncomplete(
            "This test has not been implemented yet."
        );

        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 3);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 7);

        $this->assertEquals($this->game->getWinner(), $player);

    }

    public function testGameIsTerminatedWhenOnePlayerWins()
    {
        $this->markTestIncomplete(
            "This test has not been implemented yet."
        );

        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 3);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 7);

        $this->assertTrue($this->game->isTerminated());
    }

    /**
     * @expectedException ConnectFour\GameTerminatedException
     */
    public function testPlayerCannotDropDiscsAnymoreWhenGameIsTerminated()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        $player->dropDisc($this->game, 4);
        $nextPlayer->dropDisc($this->game, 3);
        $player->dropDisc($this->game, 5);
        $nextPlayer->dropDisc($this->game, 2);
        $player->dropDisc($this->game, 6);
        $nextPlayer->dropDisc($this->game, 1);
        $player->dropDisc($this->game, 7);
      // here the first turn wins, game should be over
      $nextPlayer->dropDisc($this->game, 1);
    }

    /**
     * @expectedException ConnectFour\OutOfBoardException
     */
    public function testPlayerCannotDropDiscsAnymoreWhenColumnIsFull()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $player = $this->game->getCurrentPlayer();
        $nextPlayer = ($player == $this->player1) ? $this->player2 : $this->player1;

        for ($i = 1; $i <= Board::ROWS + 1; ++$i) {
            if ($i % 2 != 0) {
                $nextPlayer->dropDisc($this->game, 4);
            } else {
                $player->dropDisc($this->game, 4);
            }
        }
    }
}
