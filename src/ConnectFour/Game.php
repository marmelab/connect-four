<?php

namespace ConnectFour;

class Game
{
    private $turn;

    private $yellowPlayer;

    private $redPlayer;

    private $board;

    private $winner;

    public function __construct()
    {
        $this->board = new Board();
    }

    public function addPlayer(Player $player)
    {
        if (!$this->yellowPlayer) {
            $this->yellowPlayer = $player;
        } elseif (!$this->redPlayer) {
            $this->redPlayer = $player;
        // here both players are assigned, we can decide who starts
        $this->turn = rand(0, 1) == 0 ? $this->yellowPlayer : $this->redPlayer;
        } else {
            throw new TooManyPlayersException();
        }
    }

    public function getCurrentPlayer() : Player
    {
        return $this->turn;
    }

    public function isTerminated() : bool
    {
        return (bool) $this->winner;
    }

    public function getWinner() : Player
    {
        return $this->winner;
    }

    public function getBoard() : Board
    {
        return $this->board;
    }
}
