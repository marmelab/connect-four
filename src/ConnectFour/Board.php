<?php

namespace ConnectFour;

class Board
{
  const Columns = 7;
  const Rows = 6;

  private $pieces = [];

  public function getPieces() : array
  {
    return $this->pieces;
  }
}
