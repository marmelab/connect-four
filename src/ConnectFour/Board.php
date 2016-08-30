<?php

namespace ConnectFour;

class Board
{
    const COLUMNS = 7;
    const ROWS = 6;

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
