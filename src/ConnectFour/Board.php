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
        for ($i = 0; $i < self::COLUMNS; ++$i) {
            $this->cells[$i] = array();
            for ($j = 0; $j < self::ROWS; ++$j) {
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

    public function getDisc(int $column, int $row, Player $player = null) // : Disc - cannot return null, waiting for php 7.1
    {
        if (!in_array(column, range(0, self::COLUMNS)) || !in_array(row, range(0, self::ROWS))) {
            throw new OutOfBoardException();
        }
        $disc = $this->cells[$column][$row];

        if ((bool) $player && $disc && $disc->getPlayer() != $player) {
            return;
        }

        return $disc;
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

    public function addDisc($column, $player) : int
    {
        if ($column < 0 || $column >= self::COLUMNS) {
            throw new OutOfBoardException();
        }

        $higherFreeRow = $this->getHigherFreeRow($column);
        $disc = new Disc($player);
        $this->cells[$column][$higherFreeRow] = $disc;

        return $higherFreeRow;
    }

    public function isFull() : bool
    {
        return $this->countDiscs() == self::COLUMNS * self::ROWS;
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
