<?php

namespace ConnectFour;

class Disc
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
