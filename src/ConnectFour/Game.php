<?php

namespace ConnectFour;


class Game
{
  private $turn;

  private $board;

  private $winner;


  public function __construct()
  {
    $this->board = new Board();
    $this->turn = rand(0,1) == 0 ? Side::Yellow : Side::Red;
  }


  public function getCurrentTurn() : int
  {
      return $this->turn;
  }

  public function dropPiece(int $col, $side)
  {
    if($this->getCurrentTurn() != $side)
      throw new NotYourTurnException();
  }

  public function isTerminated() : bool
  {
    return !!$this->winner;
  }

  public function getWinner() : int
  {
    return $this->winner;
  }

  public function getBoard() : Board
  {
    return $this->board;
  }

}
