<?php

namespace ConnectFour;

class Board
{
    const COLUMNS = 7;
    const ROWS = 6;

    private $cells;

    public function __construct()
    {
        $this->initializeCells();
    }

    private function initializeCells()
    {
        for ($i = 0; $i <= self::COLUMNS; ++$i) {
            $this->cells[$i] = array();
            for ($j = 0; $j <= self::ROWS; ++$j) {
                $this->cells[$i][$j] = null;
            }
        }
    }

    public function reset()
    {
        $this->initializeCells();
    }

    public function getCells() : array
    {
        return $this->cells;
    }

    public function getDisc(int $column, int $row) // : Disc - cannot return null, waiting for php 7.1
    {
        return $this->cells[$column][$row];
    }

    public function countDiscs() : int
    {
        $count = 0;
        foreach ($this->cells as $row) {
            // array_filter purges null values before counting
            $count += count(array_filter($row));
        }

        return $count;
    }

    public function addDisc($column, $player)
    {
        if($col < 0 || $col >= self::COLUMNS)
        {
            throw new OutOfBoardException();
        }
        $disc = new Disc($player);
        $this->cells[$column][$this->getHigherFreeRow($column)] = $disc;
    }

    private function getHigherFreeRow($column) : int
    {
        $row = 0;

        while ($row < self::ROWS) {
            if (!$this->cells[$column][$row]) {
                return $row;
            }
            ++$row;
        }

        throw new OutOfBoardException();
    }
}
