<?php

namespace ConnectFour;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Player
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $nickname;

    public function __construct(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function getNickname() : string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function dropDisc(Game $game, int $column, bool $addMove = true)
    {
        $currentPlayer = $game->getCurrentPlayer();

        $game->addDisc($column, $this, $addMove);
    }

    public function __toString()
    {
        return sprintf('Player(%s)', $this->nickname);
    }
}
