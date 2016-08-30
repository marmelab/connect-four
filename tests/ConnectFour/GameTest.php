<?php

namespace Tests\ConnectFour;

use PHPUnit\Framework\TestCase;
use ConnectFour\Game;
use ConnectFour\Board;
use ConnectFour\Side;

class GameTest extends TestCase
{
    public function testNumberOfDiscsOnBoard()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropDisc(4, $turn);

      $nbDiscs = $game->getBoard()->countDiscs();
      $this->assertEquals($nbDiscs, 1);
    }

    /**
    * @expectedException ConnectFour\NotYourTurnException
    */
    public function testCannotDropDiscWhenNotPlayerTurn()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropDisc(4, $nextTurn);
    }

    public function testTurnAlternatesOnDroppingDisc()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropDisc(4, $turn);

      $this->assertNotEquals($turn, $nextTurn);
    }

    /**
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testAnExceptionIsThrownWhenDiscIsDroppedOutsideTheBoardOnRight(){
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropDisc(Board::Columns + 1, $turn);
    }

    /**
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testAnExceptionIsThrownWhenDiscIsDroppedOutsideTheBoardOnLeft(){
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropDisc(0, $turn);
    }

    public function testPlayerWinsWhenDropingFourAlignedDiscs()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropDisc(4, $turn);
      $game->dropDisc(3, $nextTurn);
      $game->dropDisc(5, $turn);
      $game->dropDisc(2, $nextTurn);
      $game->dropDisc(6, $turn);
      $game->dropDisc(1, $nextTurn);
      $game->dropDisc(7, $turn);

      $this->assertEquals($game->getWinner(), $turn);
    }

    public function testGameIsTerminatedWhenOnePlayerWins()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropDisc(4, $turn);
      $game->dropDisc(3, $nextTurn);
      $game->dropDisc(5, $turn);
      $game->dropDisc(2, $nextTurn);
      $game->dropDisc(6, $turn);
      $game->dropDisc(1, $nextTurn);
      $game->dropDisc(7, $turn);

      $this->assertTrue($game->isTerminated());
    }

    /**
    * @expectedException ConnectFour\GameTerminatedException
    */
    public function testPlayerCannotDropDiscsAnymoreWhenGameIsTerminated()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropDisc(4, $turn);
      $game->dropDisc(3, $nextTurn);
      $game->dropDisc(5, $turn);
      $game->dropDisc(2, $nextTurn);
      $game->dropDisc(6, $turn);
      $game->dropDisc(1, $nextTurn);
      $game->dropDisc(7, $turn);
      // here the first turn wins, game should be over
      $game->dropDisc(1, $nextTurn);
    }

    /**
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testPlayerCannotDropDiscsAnymoreWhenColumnIsFull()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      for($i = 1; $i <= Board::Rows + 1; $i++){
        $game->dropDisc(4, ($i % 2 != 0) ? $nextTurn : $turn);
      }
    }
}
