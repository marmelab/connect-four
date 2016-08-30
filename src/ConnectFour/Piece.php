<?php

namespace ConnectFour;

class Piece
{
  private int $x;

  private int $y;

  private $side;

  public function __construct(int $x, int $y, Side $side){
    $this->x = $x;
    $this->y = $y;
    $this->side = $side;
  }
}
