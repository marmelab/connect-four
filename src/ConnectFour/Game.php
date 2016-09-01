<?php

namespace ConnectFour;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Game
{
    const FINISHED = 0;
    const PLAYING = 1;
    const WAITING = 2;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="Player")
    * @ORM\JoinColumn(name="starting_player_id", referencedColumnName="id")
    */
    private $startingPlayer;

    private $currentPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="yellow_player_id", referencedColumnName="id")
     */
    private $yellowPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="red_player_id", referencedColumnName="id")
     */
    private $redPlayer;

    private $board;

    private $winner;

    /**
     * @ORM\OneToMany(targetEntity="Move", mappedBy="game", cascade={"all"})
     */
    private $moves;

    public function __construct()
    {
        $this->board = new Board();
        $this->moves = new ArrayCollection();
    }

    public function addPlayer(Player $player)
    {
        if (!$this->yellowPlayer) {
            $this->yellowPlayer = $player;
        } elseif (!$this->redPlayer) {
            $this->redPlayer = $player;
        // here both players are assigned, we can decide who starts
        $this->startingPlayer = rand(0, 1) == 0 ? $this->yellowPlayer : $this->redPlayer;
            $this->currentPlayer = $this->startingPlayer;
        } else {
            throw new TooManyPlayersException();
        }
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getCurrentPlayer() : Player
    {
        return $this->currentPlayer;
    }

    public function getRedPlayer() : Player
    {
        return $this->redPlayer;
    }

    public function getYellowPlayer() : Player
    {
        return $this->yellowPlayer;
    }

    public function getStatus() : string
    {
        if ($this->isFinished()) {
            return self::FINISHED;
        } elseif ((bool) $this->yellowPlayer && (bool) $this->redPlayer) {
            return self::PLAYING;
        } else {
            return self::WAITING;
        }
    }

    public function isFinished() : bool
    {
        //TODO : check draw
        return (bool) $this->winner;
    }

    public function getWinner() // : Player - Can't be null, waiting for php 7.1
    {
        return $this->winner;
    }

    public function getBoard() : Board
    {
        return $this->board;
    }

    public function addDisc(int $column, Player $player, bool $addMove = true)
    {
        if ($this->currentPlayer != $player) {
            throw new NotYourTurnException();
        }
        if ($addMove) {
            $move = new Move();
            $move->setGame($this);
            $move->setColumn($column);
            $move->setDate(new \DateTime());
            $move->setPlayer($player);
            $this->getMoves()->add($move);
        }
        $this->getBoard()->addDisc($column, $player);

        $this->currentPlayer = ($this->yellowPlayer == $player) ? $this->redPlayer : $this->yellowPlayer;
    }

    public function replayMoves()
    {
        $this->board = new Board();
        $this->currentPlayer = $this->startingPlayer;
        foreach ($this->moves as $move) {
            $move->getPlayer()->dropDisc($this, $move->getColumn(), false);
        }
    }

    public function setMoves($moves)
    {
        $this->moves = $moves;
    }

    public function getMoves()
    {
        return $this->moves;
    }
}
