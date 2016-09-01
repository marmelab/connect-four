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

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set column.
     *
     * @param int $column
     *
     * @return Move
     */
    public function setColumn($column)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Get column.
     *
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Move
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set game.
     *
     * @param Game $game
     *
     * @return Move
     */
    public function setGame(Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game.
     *
     * @return \AppBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set player.
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return Move
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player.
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
