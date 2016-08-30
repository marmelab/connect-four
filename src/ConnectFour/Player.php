<?php

namespace ConnectFour;

class Player
{
    private $nickname;

    public function __construct(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function getNickname() : string
    {
        return $this->nickname;
    }

    public function dropDisc(Game $game, int $col)
    {
        if ($game->getCurrentPlayer() != $this) {
            throw new NotYourTurnException();
        }
    }
}
