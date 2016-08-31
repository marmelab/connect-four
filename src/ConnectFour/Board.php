<?php

namespace ConnectFour;

class Board
{
    const COLUMNS = 7;
    const ROWS = 6;

    private $discs;

    public function __construct()
    {
        $this->initializeDiscsArray();
    }

    private function initializeDiscsArray()
    {
        for ($i = 0; $i <= self::COLUMNS; ++$i) {
            $this->discs[$i] = array();
            for ($j = 0; $j <= self::ROWS; ++$j) {
                $this->discs[$i][$j] = null;
            }
        }
    }

    public function getDiscs() : array
    {
        return $this->discs;
    }

    public function getDisc(int $col, int $row) // : Disc - cannot return null, waiting for php 7.1
    {
        return $this->discs[$col][$row];
    }

    public function countDiscs() : int
    {
        $count = 0;
        foreach ($this->discs as $row) {
            // array_filter purges null values before counting
            $count += count(array_filter($row));
        }

        return $count;
    }

    public function addDisc($col, $player)
    {
        $disc = new Disc($player);
        $this->discs[$col][$this->getHigherFreeRow($col)] = $disc;
    }

    private function getHigherFreeRow($col) : int
    {
        $row = 0;

        while ($row < self::ROWS) {
            if (!$this->discs[$col][$row]) {
                return $row;
            }
            ++$row;
        }

        throw new OutOfBoardException();
    }

}
