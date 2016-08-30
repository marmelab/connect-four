<?php

namespace Tests\ConnectFour;

use PHPUnit\Framework\TestCase;
use ConnectFour\Game;
use ConnectFour\Board;
use ConnectFour\Side;

class GameTest extends TestCase
{
    public function testNumberOfPiecesOnBoard()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropPiece(4, $turn);

      $nbPieces = count($game->getBoard()->getPieces());
      $this->assertEquals($nbPieces, 1);
    }

    /**
    * @expectedException ConnectFour\NotYourTurnException
    */
    public function testCannotDropDiscWhenNotPlayerTurn()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropPiece(4, $nextTurn);
    }

    public function testTurnAlternatesOnDroppingDisc()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropPiece(4, $turn);

      $this->assertNotEquals($turn, $nextTurn);
    }

    /**
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testAnExceptionIsThrownWhenDiscIsDroppedOutsideTheBoardOnRight(){
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropPiece(Board::Columns + 1, $turn);
    }

    /**
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testAnExceptionIsThrownWhenDiscIsDroppedOutsideTheBoardOnLeft(){
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropPiece(0, $turn);
    }

    public function testPlayerWinsWhenDropingFourAlignedDiscs()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropPiece(4, $turn);
      $game->dropPiece(3, $nextTurn);
      $game->dropPiece(5, $turn);
      $game->dropPiece(2, $nextTurn);
      $game->dropPiece(6, $turn);
      $game->dropPiece(1, $nextTurn);
      $game->dropPiece(7, $turn);

      $this->assertEquals($game->getWinner(), $turn);
    }

    public function testGameIsTerminatedWhenOnePlayerWins()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropPiece(4, $turn);
      $game->dropPiece(3, $nextTurn);
      $game->dropPiece(5, $turn);
      $game->dropPiece(2, $nextTurn);
      $game->dropPiece(6, $turn);
      $game->dropPiece(1, $nextTurn);
      $game->dropPiece(7, $turn);

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

      $game->dropPiece(4, $turn);
      $game->dropPiece(3, $nextTurn);
      $game->dropPiece(5, $turn);
      $game->dropPiece(2, $nextTurn);
      $game->dropPiece(6, $turn);
      $game->dropPiece(1, $nextTurn);
      $game->dropPiece(7, $turn);
      // here the first turn wins, game should be over
      $game->dropPiece(1, $nextTurn);
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
        $game->dropPiece(4, ($i % 2 != 0) ? $nextTurn : $turn);
      }
    }
}
