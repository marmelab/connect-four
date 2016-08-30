<?php

namespace Tests\ConnectFour;

use PHPUnit\Framework\TestCase;
use ConnectFour\Game;
use ConnectFour\Board;
use ConnectFour\Side;

class GameTest extends TestCase
{
    /**
    * Tests that the number of pieces of the board is right
    */
    public function testPiecesOnBoard()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropPiece(4, $turn);

      $nbPieces = count($game->getBoard()->getPieces());
      $this->assertEquals($nbPieces, 1);
    }

    /**
    * Tests that I cannot take the wrong turn
    * @expectedException ConnectFour\NotYourTurnException
    */
    public function testCannotCheatTurns()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropPiece(4, $nextTurn);
    }

    /**
    * Tests that turn alternates when I play
    */
    public function testTurnAlternates()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      $game->dropPiece(4, $turn);

      $this->assertNotEquals($turn, $nextTurn);
    }

    /**
    * Tests that I cannot drop a piece outside the board on the right
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testBoardSizeMax(){
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropPiece(Board::Columns + 1, $turn);
    }

    /**
    * Tests that I cannot drop a piece outside the board on the left
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testBoardSizeMin(){
      $game = new Game();
      $turn = $game->getCurrentTurn();

      $game->dropPiece(0, $turn);
    }

    /**
    * Tests that I win
    */
    public function testGameGetWinner()
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

    /**
    * Tests that game is terminated
    */
    public function testGameIsTerminated()
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
    * Tests that I cannot take turn anymore when game is terminated
    * @expectedException ConnectFour\GameTerminatedException
    */
    public function testCannotPlayWhenTerminated()
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
    * Tests that I cannot drop a piece if column is full
    * @expectedException ConnectFour\OutOfBoardException
    */
    public function testColumnIsFull()
    {
      $game = new Game();
      $turn = $game->getCurrentTurn();
      $nextTurn = ($turn == Side::Red) ? Side::Yellow : Side::Red;

      for($i = 1; $i <= Board::Rows + 1; $i++){
        $game->dropPiece(4, ($i % 2 != 0) ? $nextTurn : $turn);
      }
    }
}
