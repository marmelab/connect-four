<?php

namespace ConnectFour;

class Disc
{
  private int $x;

  private int $y;

  private $player;

  public function __construct(int $x, int $y, Player $player){
    $this->x = $x;
    $this->y = $y;
    $this->player = $player;
  }
}
