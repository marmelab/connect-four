<?php

namespace ConnectFour;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Game
{
    const WAITING = 0;
    const PLAYING = 1;
    const FINISHED = 2;

    const FIRST_PLAYER_COLOR = 'yellow';
    const SECOND_PLAYER_COLOR = 'red';

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
     * @ORM\JoinColumn(name="first_player_id", referencedColumnName="id")
     */
    private $firstPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="second_player_id", referencedColumnName="id")
     */
    private $secondPlayer;

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
        if (!$this->firstPlayer) {
            $this->firstPlayer = $player;
        } elseif (!$this->secondPlayer) {
            $this->secondPlayer = $player;
            // here both players are assigned, we can decide who starts
            $this->startingPlayer = rand(0, 1) == 0 ? $this->firstPlayer : $this->secondPlayer;
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

    public function getSecondPlayer() : Player
    {
        return $this->secondPlayer;
    }

    public function getFirstPlayer() : Player
    {
        return $this->firstPlayer;
    }

    public function getStatus() : string
    {
        if ($this->isFinished()) {
            return self::FINISHED;
        } elseif ((bool) $this->firstPlayer && (bool) $this->secondPlayer) {
            return self::PLAYING;
        }

        return self::WAITING;
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
            $move = new Move($this, $column, $player, new \DateTime());
            $this->getMoves()->add($move);
        }
        $this->getBoard()->addDisc($column, $player);

        $this->switchPlayer();
    }

    private function switchPlayer()
    {
        $this->currentPlayer = ($this->firstPlayer == $this->currentPlayer) ? $this->secondPlayer : $this->firstPlayer;
    }

    public function replayMoves()
    {
        $this->board = new Board();
        $this->currentPlayer = $this->startingPlayer;
        foreach ($this->moves as $move) {
            $move->getPlayer()->dropDisc($this, $move->getColumn(), false);
        }
    }

    public function getPlayerColor(Player $player) : string
    {
        return $this->getFirstPlayer() == $player
            ? self::FIRST_PLAYER_COLOR
            : self::SECOND_PLAYER_COLOR;
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
