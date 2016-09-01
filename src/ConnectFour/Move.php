<?php

namespace ConnectFour;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Move
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="col")
     */
    private $column;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="moves")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    private $player;


    public function __construct(Game $game, int $column, Player $player, \DateTime $date)
    {
        $this->game = $game;
        $this->column = $column;
        $this->player = $player;
        $this->date = $date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setColumn(int $column)
    {
        $this->column = $column;

        return $this;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setGame(Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setPlayer(Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    public function getPlayer()
    {
        return $this->player;
    }
}
