<?php

namespace ConnectFour;

class Board
{
  const Columns = 7;
  const Rows = 6;

  private $discs = [];

  public function getDiscs() : array
  {
    return $this->discs;
  }

  public function countDiscs() : int
  {
    return count($this->discs);
  }
}
