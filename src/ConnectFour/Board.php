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

    public function getDisc(int $column, int $row) // : Disc - cannot return null, waiting for php 7.1
    {
        return $this->discs[$column][$row];
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

    public function addDisc($column, $player)
    {
        $disc = new Disc($player);
        $this->discs[$column][$this->getHigherFreeRow($column)] = $disc;
    }

    private function getHigherFreeRow($column) : int
    {
        $row = 0;

        while ($row < self::ROWS) {
            if (!$this->discs[$column][$row]) {
                return $row;
            }
            ++$row;
        }

        throw new OutOfBoardException();
    }
}
